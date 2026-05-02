# Pitchly — AI-Powered Sales Page Generator

> Generate high-converting sales pages in ~90 seconds using GPT-4o.

Pitchly is a Laravel 13 web application that lets users create fully standalone HTML sales pages by providing basic product information. The app calls OpenAI's GPT-4o to write all copy, fetches a relevant hero image from Unsplash, and renders a production-ready sales page — no design or copywriting skills needed.

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [System Architecture](#system-architecture)
  - [High-Level Flow](#high-level-flow)
  - [Directory Structure](#directory-structure)
  - [Data Model](#data-model)
  - [Service Layer](#service-layer)
  - [Async Job Queue](#async-job-queue)
  - [Authentication & Authorization](#authentication--authorization)
  - [Frontend Architecture](#frontend-architecture)
- [API & Routes](#api--routes)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Environment Variables](#environment-variables)
  - [Running the Development Server](#running-the-development-server)
- [How It Works (Deep Dive)](#how-it-works-deep-dive)
  - [Initial Page Generation](#initial-page-generation)
  - [Refinement & Versioning](#refinement--versioning)
  - [OpenAI Prompt Design](#openai-prompt-design)
- [Testing](#testing)
- [Code Quality](#code-quality)
- [Deployment Notes](#deployment-notes)

---

## Features

- **AI Sales Page Generation** — Provide product name, description, features, target audience, price, and USP; get a complete standalone HTML sales page.
- **Hero Image via Unsplash** — Automatically fetches a contextually relevant hero image. Falls back to curated defaults if the Unsplash API key is absent.
- **Two Design Templates** — `modern` (violet/indigo gradient, light background) and `bold` (black background, yellow accents).
- **Version History & Refinement** — Regenerate any page with user feedback. Every version is tracked; originals and their refinements are linked via a parent-child relationship.
- **Async Processing** — Generation runs in a background queue job. The UI polls for status and updates the page live upon completion.
- **Export** — Download the generated page as a self-contained `.html` file.
- **User Accounts** — Full registration, login, email verification, and password reset via Laravel Breeze.
- **Per-User Isolation** — All pages are private to the creating user. Policy-based authorization enforced on every action.
- **Rate Limiting** — OpenAI-backed endpoints (create, regenerate) are throttled to 5 requests/minute per user.

---

## Tech Stack

| Layer | Technology |
|---|---|
| **Backend Framework** | Laravel 13 (PHP 8.3+) |
| **Frontend** | Blade templates, Alpine.js v3, Tailwind CSS v3, Vite 8 |
| **AI** | OpenAI GPT-4o via `openai-php/laravel` SDK |
| **Image API** | Unsplash |
| **Database** | MySQL (production), SQLite (development & tests) |
| **Queue** | Laravel database queue |
| **Auth** | Laravel Breeze (email + password) |
| **Testing** | PHPUnit 12 |
| **Code Style** | Laravel Pint (PSR-12) |

---

## System Architecture

### High-Level Flow

```
Browser                 Laravel App                 External APIs
  │                          │                            │
  │── POST /sales-pages ────►│                            │
  │                          │── fetchUnsplashImage() ───►│ Unsplash
  │                          │◄─ image URL ───────────────│
  │                          │                            │
  │                          │ Create SalesPage (pending) │
  │                          │ Dispatch GenerateSalesPageJob
  │                          │                            │
  │◄── redirect to /show ────│                            │
  │                          │                            │
  │── GET /status (poll) ───►│                            │
  │                          │  [Queue Worker Process]    │
  │                          │── callOpenAI() ───────────►│ OpenAI GPT-4o
  │                          │◄─ generated HTML ──────────│
  │                          │                            │
  │                          │ Update SalesPage (complete)│
  │                          │                            │
  │◄── { status: complete } ─│                            │
  │── reload page ──────────►│                            │
  │◄── preview + download ───│                            │
```

### Directory Structure

```
ai-sales-page/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── SalesPageController.php   # CRUD, status polling, export
│   │   │   ├── ProfileController.php
│   │   │   └── Auth/                     # Breeze auth controllers
│   │   └── Requests/                     # Form validation classes
│   ├── Models/
│   │   ├── SalesPage.php                 # Core data model + accessors + scopes
│   │   └── User.php
│   ├── Services/
│   │   └── SalesPageService.php          # All OpenAI & Unsplash integration
│   ├── Jobs/
│   │   └── GenerateSalesPageJob.php      # Async generation job
│   └── Policies/
│       └── SalesPagePolicy.php           # Authorization rules
│
├── database/
│   └── migrations/
│       ├── ..._create_users_table.php
│       ├── ..._create_jobs_table.php
│       ├── ..._create_sales_pages_table.php
│       └── ..._add_status_to_sales_pages_table.php
│
├── resources/views/
│   ├── layouts/
│   │   ├── main.blade.php                # Authenticated dashboard shell
│   │   └── guest.blade.php              # Public/auth layout
│   ├── sales-pages/
│   │   ├── index.blade.php              # Page history / dashboard
│   │   ├── create.blade.php             # Generation form (Alpine.js)
│   │   └── show.blade.php              # Preview + refinement UI
│   └── landing.blade.php               # Public marketing page
│
├── routes/
│   └── web.php                          # All route definitions
│
├── config/
│   ├── openai.php                        # OpenAI SDK config
│   └── services.php                     # Unsplash key config
│
├── vite.config.js
├── tailwind.config.js
├── phpunit.xml
├── composer.json
└── package.json
```

### Data Model

The `sales_pages` table is the core of the application.

```
sales_pages
├── id                  PK
├── user_id             FK → users (cascade delete)
├── product_name
├── description
├── features            Comma-separated string (use $page->features_array accessor)
├── target_audience
├── price
├── unique_selling_point
├── generated_html      Full standalone HTML from GPT-4o
├── hero_image_url      From Unsplash; reused on regeneration (no extra API call)
├── template            'modern' | 'bold'
├── parent_id           NULL for originals; FK → sales_pages for refined versions
├── feedback            User's refinement instructions (null for originals)
├── version             1 for originals; incremented on each regeneration
├── status              'pending' | 'generating' | 'completed' | 'failed'
├── error_message       Stores API error detail on failure
└── timestamps
```

**Key model features:**

```php
// Accessor — split comma-separated string into array
$page->features_array

// Scope — originals only (excludes child versions)
SalesPage::originals()->get()

// Scope — keyword search on product_name
SalesPage::search($term)->get()

// Boolean check
$page->isOriginal  // true when parent_id === null
$page->versions()  // hasMany relationship to child regenerations
```

### Service Layer

`app/Services/SalesPageService.php` contains all integration logic. Controllers never call APIs directly.

| Method | Responsibility |
|---|---|
| `generate(array $data, string $template)` | Orchestrates Unsplash + OpenAI for initial generation |
| `fetchUnsplashImage(string $productName)` | Calls Unsplash; extracts keywords; falls back gracefully |
| `callOpenAI(array $data, string $heroUrl, string $template)` | Builds prompt, calls GPT-4o, strips markdown fences, retries on 429/503 |
| `regenerate(array $originalData, string $originalHtml, string $heroUrl, string $feedback, string $template)` | Multi-turn conversation with GPT-4o for feedback-based refinement |
| `systemPrompt()` | Returns the copywriting rules system prompt |
| `buildPrompt(...)` | Builds the user-turn prompt with product data + design spec |
| `styleGuide(string $template)` | Returns color palette and style rules for the chosen template |
| `buildRegeneratePrompt(string $feedback)` | Returns the feedback-injection user message |

### Async Job Queue

Long-running GPT-4o calls (sometimes 20–60 seconds) run inside `GenerateSalesPageJob`, not in the HTTP request lifecycle.

```
SalesPageController@store
  └── SalesPage::create(['status' => 'pending', ...])
  └── GenerateSalesPageJob::dispatch($salesPage)
         │
         ▼  [queue:listen worker]
  GenerateSalesPageJob::handle()
    ├── SalesPageService->generate()   [initial]
    │     ├── fetchUnsplashImage()
    │     └── callOpenAI()
    └── SalesPageService->regenerate() [refinement]
          └── callOpenAI() with multi-turn context
```

Job configuration:
- **Driver:** database queue
- **Timeout:** 200 seconds
- **Max attempts:** 2
- **Backoff:** 10s, 30s (exponential)
- **On success:** `status = 'completed'`, HTML saved
- **On failure:** `status = 'failed'`, error message stored

The browser polls `GET /sales-pages/{id}/status` every few seconds. When the job completes, the UI auto-reloads to show the result.

### Authentication & Authorization

**Auth:** Laravel Breeze — register, login, email verification, password reset.

**Authorization:** `SalesPagePolicy` gates every action. All methods check `$user->id === $salesPage->user_id`. Policies are registered automatically via Laravel's convention discovery.

```php
// Explicit authorize() call in every controller action
$this->authorize('view', $salesPage);
$this->authorize('delete', $salesPage);
```

**Rate limiting:** A custom `openai` throttle (5 req/min per user) is applied to `store` and `regenerate` routes to prevent abuse.

### Frontend Architecture

**Create form** (`resources/views/sales-pages/create.blade.php`):
- Alpine.js manages form state, character counters, and loading overlay.
- On submit, sends a `fetch` request to `POST /sales-pages`.
- On job complete, redirects to the preview page.
- Shows an animated progress overlay with rotating status messages during generation.

**Preview page** (`resources/views/sales-pages/show.blade.php`):
- Embeds the generated HTML in an `<iframe>`.
- Sidebar shows full version history (original + all refinements).
- Refinement form submits feedback to `POST /sales-pages/{id}/regenerate`.
- Topbar provides Copy HTML, Download, and Delete actions.

**Dashboard** (`resources/views/sales-pages/index.blade.php`):
- Grid view of original pages (12 per page, server-side pagination).
- Search by product name via `?q=` query parameter.
- Each card displays version count, creation date, and quick-action links.

**Layout** (`resources/views/layouts/main.blade.php`):
- Fixed sidebar on desktop, animated drawer on mobile.
- Sidebar toggle controlled by Alpine.js.

---

## API & Routes

All routes under `/sales-pages` require authentication.

| Method | URI | Controller Action | Description |
|---|---|---|---|
| `GET` | `/` | Landing / redirect | Public marketing page |
| `GET` | `/sales-pages` | `index` | List original pages (paginated, searchable) |
| `GET` | `/sales-pages/create` | `create` | Generation form |
| `POST` | `/sales-pages` | `store` | Validate, create pending record, dispatch job |
| `GET` | `/sales-pages/{id}` | `show` | Preview + version history |
| `DELETE` | `/sales-pages/{id}` | `destroy` | Delete original + all child versions |
| `POST` | `/sales-pages/{id}/regenerate` | `regenerate` | Accept feedback, create new version, dispatch job |
| `GET` | `/sales-pages/{id}/export` | `export` | Download generated HTML as a file |
| `GET` | `/sales-pages/{id}/status` | `status` | JSON status polling endpoint |

---

## Getting Started

### Prerequisites

- PHP 8.3+
- Composer
- Node.js 20+ and npm
- MySQL 8+ (or SQLite for development)
- An [OpenAI API key](https://platform.openai.com/api-keys) with access to `gpt-4o`
- (Optional) An [Unsplash Access Key](https://unsplash.com/developers)

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/your-username/pitchly.git
cd pitchly

# 2. Run first-time setup (installs deps, generates key, runs migrations, builds assets)
composer setup
```

`composer setup` runs: `composer install` → `php artisan key:generate` → `php artisan migrate` → `npm install` → `npm run build`

### Environment Variables

Copy `.env.example` to `.env` and fill in the required values:

```env
APP_NAME=Pitchly
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pitchly
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database

# Required — page generation will fail without this
OPENAI_API_KEY=sk-proj-...

# Optional — app falls back to curated images if absent
UNSPLASH_ACCESS_KEY=your_unsplash_access_key
```

### Running the Development Server

```bash
composer dev
```

This starts four concurrent processes:
1. `php artisan serve` — Laravel HTTP server on `http://localhost:8000`
2. `php artisan queue:listen` — Queue worker for async generation jobs
3. `php artisan pail` — Real-time log viewer
4. `npm run dev` — Vite HMR dev server

---

## How It Works (Deep Dive)

### Initial Page Generation

1. User fills out the form on `/sales-pages/create`: product name, description, features, target audience, price, USP, and template choice.
2. `SalesPageController@store` validates the input and creates a `SalesPage` row with `status = 'pending'`.
3. `GenerateSalesPageJob` is dispatched to the database queue.
4. User is redirected to the preview page, which shows a loading overlay and begins polling `/sales-pages/{id}/status`.
5. The queue worker picks up the job and calls `SalesPageService@generate`:
   - `fetchUnsplashImage()` sends a request to the Unsplash API using keywords extracted from the product name (stop words removed). If the key is missing or the request fails, a curated fallback URL is used.
   - `callOpenAI()` constructs a structured prompt (see below) and sends it to `gpt-4o`. Retries are applied on HTTP 429 (rate limit) and 503 (server error). Markdown fences are stripped from the response.
6. The `SalesPage` row is updated: `generated_html` and `hero_image_url` are saved, `status` is set to `'completed'`.
7. The browser's status poll detects `'completed'` and reloads the page to show the rendered sales page in an iframe.

### Refinement & Versioning

Each refinement creates a **new** `SalesPage` row — originals are never overwritten.

```
Original (parent_id: null, version: 1)
├── Refinement A (parent_id: original.id, version: 2, feedback: "make hero bolder")
└── Refinement B (parent_id: original.id, version: 3, feedback: "add urgency to CTA")
```

The refinement flow always regenerates from the **root original's** product data, even when refining an already-refined version. This prevents content drift. The hero image URL is reused — no additional Unsplash API call is made.

The refinement uses a **multi-turn OpenAI conversation**:
```
[system]     → Copywriting rules
[user]       → Original generation prompt (with product data)
[assistant]  → Original generated HTML
[user]       → "The user has requested the following changes: {feedback}. Apply them..."
```

This gives GPT-4o full context of what was generated and what to change, resulting in precise, coherent edits.

### OpenAI Prompt Design

The prompt system has four layers:

1. **System prompt** — Enforces direct-response copywriting rules: the PAS framework (Problem → Agitate → Solve), a banned-words list (no "unleash", "leverage", "game-changer", etc.), specificity requirements, and emoji-as-icon guidelines.

2. **Design system prompt** — Specifies all 10 sales page sections with exact structural requirements:
   - Sticky navbar with logo + CTA
   - Hero with headline, subheadline, CTA, social proof counter, and hero image
   - Story section (PAS narrative)
   - Benefits grid (3 cards)
   - Features list
   - Testimonials (3, with realistic names and avatars via UI Avatars API)
   - Pricing section (single tier)
   - FAQ accordion
   - Final CTA section
   - Footer

3. **Style guide** — Injected per template:
   - `modern`: violet/indigo gradient, white/gray backgrounds, rounded-2xl cards, shadow-lg
   - `bold`: black background, yellow accent (#FFD700), uppercase headings, high contrast

4. **Language rule** — Instructs GPT-4o to match the language of the input data (English, Indonesian, etc.) throughout the entire generated HTML.

---

## Testing

```bash
# Run the full test suite
composer test

# Run a specific test class
php artisan test --filter=SalesPageTest

# Run a directory
php artisan test tests/Feature/Auth/
```

Test environment uses an in-memory SQLite database. The queue driver is set to `sync` so jobs run immediately and inline during tests. Laravel Breeze auth tests are included by default.

---

## Code Quality

```bash
# Format all PHP files to PSR-12 (Laravel Pint)
./vendor/bin/pint
```

---

## Deployment Notes

There is currently no Docker or CI/CD configuration. For a production deployment:

1. **Server requirements:** PHP 8.3+, MySQL 8+, Composer, Node.js (for asset build), a process manager (Supervisor) for the queue worker.
2. **Build assets:** Run `npm run build` — compiled files land in `public/build/`.
3. **Queue worker:** Run `php artisan queue:work --tries=2 --timeout=200` under Supervisor so it restarts on crash.
4. **Environment:** Set `APP_ENV=production`, `APP_DEBUG=false`, and all required env vars.
5. **Caching:** Run `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache` for performance.
6. **OPENAI_API_KEY** must be set — page generation is entirely dependent on it.

---

## License

MIT
