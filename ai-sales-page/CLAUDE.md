# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

### Development
```bash
composer dev        # Start all services concurrently: PHP server, queue, log viewer (Pail), Vite HMR
composer setup      # First-time setup: install deps, generate key, migrate, build assets
```

### Testing
```bash
composer test                              # Run full PHPUnit test suite
php artisan test --filter=SalesPageTest    # Run a single test class
php artisan test tests/Feature/Auth/       # Run a directory of tests
```

### Frontend
```bash
npm run dev     # Vite dev server (usually started via composer dev)
npm run build   # Production asset build
```

### Database
```bash
php artisan migrate          # Run pending migrations
php artisan migrate:fresh    # Drop all tables and re-run migrations
php artisan tinker           # Laravel REPL for debugging models/queries
```

### Code Quality
```bash
./vendor/bin/pint            # Laravel Pint (PHP CS Fixer — PSR-12 style)
```

## Architecture

### Overview
This is a Laravel 13 / PHP 8.3 web app that generates AI-powered sales pages. Users provide product details; the app fetches a hero image from Unsplash and calls GPT-4o to produce a standalone HTML sales page. Users can preview, refine (regenerate with feedback), export, and delete their pages.

**Stack:** Laravel 13 · Blade templates · Tailwind CSS v3 · Alpine.js · Vite · SQLite (dev) · OpenAI PHP SDK · Laravel Breeze (auth)

### Key Data Model: `sales_pages` table
- **Originals** have `parent_id = null`, `version = 1`.
- **Regenerations** have `parent_id` pointing to the original, `version > 1`, and a `feedback` field storing what the user asked to change.
- `features` is a comma-separated string (not JSON). Use `$salesPage->features_array` accessor to get it as an array.
- `generated_html` stores the full standalone HTML file produced by OpenAI.
- `hero_image_url` is reused across regenerations (no new Unsplash call on refine).

### Core Request Flow
1. User submits form → `SalesPageController@store`
2. Controller delegates to `SalesPageService@generate`:
   - `fetchUnsplashImage()` — calls Unsplash API (or falls back to a hardcoded URL if key missing)
   - `callOpenAI()` — sends system + user prompt to GPT-4o, strips markdown fences from response
3. Resulting HTML + image URL saved as a new `SalesPage` row
4. Redirect to `sales-pages.show`

**Regeneration** (`SalesPageController@regenerate`) follows a multi-turn OpenAI conversation: original prompt → original HTML (as assistant turn) → feedback prompt. Always regenerates from the root original's data, even when refining a previously refined version.

### Service Layer: `SalesPageService`
All OpenAI and Unsplash logic lives here. The prompts are long and opinionated — the system prompt enforces copywriting rules (banned words list, specificity, no AI clichés). Two templates exist: `modern` (violet/indigo, light bg) and `bold` (black bg, yellow accents).

### Authorization
`SalesPagePolicy` gates `view`, `update`, and `delete` — all scoped to `$user->id === $salesPage->user_id`. Controllers call `$this->authorize()` explicitly; no global middleware.

### Routes (`routes/web.php`)
All sales page routes require `auth` middleware. The resource route covers `index`, `create`, `store`, `show`, `destroy`. Two extra named routes exist:
- `POST sales-pages/{salesPage}/regenerate` → `regenerate`
- `GET sales-pages/{salesPage}/export` → `export` (downloads as `.html` file)

### Required Environment Variables
```
OPENAI_API_KEY=          # Required — GPT-4o calls will fail without this
UNSPLASH_ACCESS_KEY=     # Optional — falls back to hardcoded images if missing
```

### Test Environment
Tests use an in-memory SQLite database (`DB_DATABASE=:memory:`). Queue is set to `sync`. Pulse, Telescope, and Nightwatch are disabled. Authentication tests come from Laravel Breeze defaults.
