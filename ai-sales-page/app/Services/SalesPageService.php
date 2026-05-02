<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class SalesPageService
{
    // Fallback images per generic category — used when Unsplash is unavailable
    private const FALLBACK_IMAGES = [
        'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1600&q=80',
        'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1600&q=80',
        'https://images.unsplash.com/photo-1551434678-e076c223a692?w=1600&q=80',
    ];

    // ── Public entry point ─────────────────────────────────────────────────

    /**
     * Generate a complete sales page from product data.
     *
     * @param  array{
     *   product_name: string,
     *   description: string,
     *   features: string,
     *   target_audience: string,
     *   price: string,
     *   unique_selling_point: string,
     * } $data
     * @param  string $template  'modern' | 'bold'
     * @return array{html: string, hero_image_url: string}
     *
     * @throws \RuntimeException  when OpenAI returns an empty response
     */
    public function generate(array $data, string $template = 'modern'): array
    {
        $heroImageUrl = $this->fetchUnsplashImage($data['product_name']);

        $html = $this->callOpenAI($data, $heroImageUrl, $template);

        return [
            'html'           => $html,
            'hero_image_url' => $heroImageUrl,
        ];
    }

    // ── Unsplash ───────────────────────────────────────────────────────────

    private function fetchUnsplashImage(string $productName): string
    {
        $accessKey = config('services.unsplash.access_key');

        // If no key configured, skip the API call
        if (empty($accessKey) || $accessKey === 'your-unsplash-access-key') {
            return $this->fallbackImage();
        }

        // Distil a clean keyword from the product name
        $keyword = $this->extractImageKeyword($productName);

        try {
            $response = Http::timeout(8)
                ->withHeaders(['Authorization' => "Client-ID {$accessKey}"])
                ->get('https://api.unsplash.com/photos/random', [
                    'query'       => $keyword,
                    'orientation' => 'landscape',
                    'content_filter' => 'high',
                ]);

            if ($response->successful()) {
                $url = $response->json('urls.regular');
                if ($url) {
                    // Append size params for performance
                    return $url . '&w=1600&q=80&fit=crop';
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Unsplash API failed', [
                'keyword' => $keyword,
                'error'   => $e->getMessage(),
            ]);
        }

        return $this->fallbackImage();
    }

    private function extractImageKeyword(string $productName): string
    {
        // Strip common filler words, keep nouns that make good photo searches
        $stopWords = ['the', 'a', 'an', 'for', 'by', 'with', 'and', 'or', 'pro', 'plus', 'app', 'io'];
        $words     = preg_split('/\s+/', strtolower($productName));
        $filtered  = array_filter($words, fn($w) => !in_array($w, $stopWords) && strlen($w) > 2);

        return implode(' ', array_slice(array_values($filtered), 0, 3)) ?: 'technology workspace';
    }

    private function fallbackImage(): string
    {
        return self::FALLBACK_IMAGES[array_rand(self::FALLBACK_IMAGES)];
    }

    // ── OpenAI ────────────────────────────────────────────────────────────

    private function callOpenAI(array $data, string $heroImageUrl, string $template): string
    {
        $prompt = $this->buildPrompt($data, $heroImageUrl, $template);

        $response = retry(
            times: 2,
            callback: fn () => OpenAI::chat()->create([
                'model'       => 'gpt-4o',
                'temperature' => 0.6,
                'max_tokens'  => 16000,
                'messages'    => [
                    ['role' => 'system', 'content' => $this->systemPrompt()],
                    ['role' => 'user',   'content' => $prompt],
                ],
            ]),
            sleepMilliseconds: fn (int $attempt) => $attempt * 5000,
            when: fn (\Throwable $e) => $this->isRetryableError($e),
        );

        $html = trim($response->choices[0]->message->content ?? '');

        if (empty($html)) {
            throw new \RuntimeException('OpenAI returned an empty response.');
        }

        // Strip markdown code fences if the model wrapped the output
        $html = preg_replace('/^```(?:html)?\s*/i', '', $html);
        $html = preg_replace('/\s*```$/', '', $html);

        return trim($html);
    }

    // ── Prompts ───────────────────────────────────────────────────────────

    private function systemPrompt(): string
    {
        return <<<'SYS'
You are a senior direct-response copywriter and front-end developer who builds sales pages that convert.

COPYWRITING RULES:
- Use the PAS framework: Problem → Agitate → Solution
- Headlines must create curiosity or state a specific measurable outcome
- Every section must answer the reader's silent question: "So what? Why should I care?"
- Write like a smart friend explaining over coffee — specific, direct, zero fluff
- Testimonials must include specific numbers or concrete results, not vague praise
- Every CTA must be different — vary the text based on where the user is in the page journey
- Pricing section must show ALL plans completely with full feature lists — never truncate

TECHNICAL RULES:
- Output only valid, complete HTML. No markdown. No explanation. No code fences.
- CRITICAL: For icons in Benefits and Features sections, ALWAYS use this EXACT pattern: <div class="w-14 h-14 rounded-2xl bg-{color}-100 flex items-center justify-center"><span class="text-2xl">emoji</span></div>. Emoji icon containers must NEVER be smaller than w-14 h-14. The emoji inside must NEVER be smaller than text-2xl. If you make them smaller, the page will look broken. Choose emoji that clearly represent each feature — 📊 analytics, 👥 teams, ⚡ speed, 🔒 security, ⚙️ settings, ✅ quality, 📈 growth, 💬 communication, 📄 documents, 🌐 global, ⭐ ratings, 🔥 popular, 📅 scheduling, 🧪 testing, 🤖 AI, 💰 pricing, 🧩 integrations, 🎨 design, 📷 photos, 🎬 video, 📁 files, ✨ premium.
- The HTML must be 100% complete — every section fully rendered, every pricing plan fully detailed, with a footer containing copyright and placeholder links
- Never cut off mid-section. If running long, simplify prose rather than omitting sections.

COLOR PALETTE RULES:
- Do NOT always use violet/purple for modern or yellow for bold templates.
- Analyze the product type and choose a color palette that fits the industry:
  - Tech/SaaS: blue-600, cyan-500, slate tones
  - Health/Beauty/Skincare: rose-500, pink-400, warm neutrals
  - Food/Restaurant: orange-500, amber-400, warm browns
  - Education/Course: indigo-600, sky-500, soft blues
  - Creative/Design: fuchsia-500, purple-600, vibrant gradients
  - E-commerce/Retail: violet-600, red-500, modern neutrals
  - Fitness/Sports: lime-500, green-600, energetic tones
  - Real Estate: amber-700, stone tones, elegant neutrals
  - Photography/Video: gray-900, neutral tones, minimal accents
  - Finance/Business: emerald-600, teal-500, dark navy
- The color choice should feel natural for the product being described.
- Use the chosen color consistently across: navbar CTA, hero gradient, buttons, section accents, pricing highlight, and footer.
- Modern template: light backgrounds with the accent color for CTAs, gradients, and highlights.
- Bold template: dark background (#0a0a0a or equivalent dark) with the accent color replacing yellow.

LANGUAGE RULES:
- Detect the language of the user's input (product name, description, features, etc.)
- ALL generated copy (headlines, body text, CTAs, testimonials, section titles, FAQ, footer) MUST be in the SAME language as the input.
- If the input is in Indonesian (Bahasa Indonesia), the entire page must be in Indonesian.
- If the input is in English, the entire page must be in English.
- Do NOT mix languages. Do NOT translate the input to English if it was given in another language.
- Button text, navigation labels, section headers — everything must match the input language.
SYS;
    }

    private function buildPrompt(array $data, string $heroImageUrl, string $template): string
    {
        $features   = implode(', ', array_filter(array_map('trim', explode(',', $data['features']))));
        $styleGuide = $this->styleGuide($template);
        $avatarBase = 'https://ui-avatars.com/api/?background=7c3aed&color=fff&bold=true&size=96&rounded=true';

        return <<<PROMPT
Generate a complete, professional sales page HTML file for this product.

PRODUCT DETAILS:
- Name: {$data['product_name']}
- Description: {$data['description']}
- Key features: {$features}
- Target audience: {$data['target_audience']}
- Price: {$data['price']}
- Unique selling point: {$data['unique_selling_point']}

Based on the product description and target audience above, choose an appropriate color palette that matches the industry/vibe of this product. Do NOT default to violet — pick colors that feel natural for this specific product.

HERO IMAGE URL (use exactly as-is for the hero background):
{$heroImageUrl}

AVATAR BASE URL for testimonials (append ?name=First+Last to this):
https://ui-avatars.com/api/?background=7c3aed&color=fff&bold=true&size=96&rounded=true

DESIGN STYLE:
{$styleGuide}

COPYWRITING RULES — follow these strictly:
1. NEVER use these words: Discover, Unleash, Empower, Transform, Revolutionary, Game-changer, Cutting-edge, Seamless, Leverage, Holistic, Robust, Synergy, Paradigm, Next-level, World-class, Supercharge, Elevate, Unlock
2. Headlines must be direct and benefit-specific — not poetic or vague. Max 10 words.
3. Body copy uses flowing prose, not excessive bullets. Write like a smart founder explaining to a friend.
4. Testimonials sound real: include specific details ("saves me about 2 hours every Friday"), mild imperfection, a real job title. Not: "This product changed my life!" — that's fake.
5. Numbers beat adjectives. "Ships in 3 days" beats "ships fast". "Used by 4,200 teams" beats "used by thousands".
6. CTA buttons use active, specific text — not just "Get Started". Examples: "Start free trial", "Get {$data['product_name']}", "See it in action".
7. Hero headline MUST mention a specific measurable outcome — e.g., "Get 3× More Replies Without Writing a Single Email"
8. Sub-headline must clarify who the target is and exactly what they get
9. Never start a section with "Our" or the product name — start with the problem or the outcome the reader cares about
10. IMPORTANT: Detect the language of the product details above and write ALL copy in that same language. If the input is Indonesian, write entirely in Indonesian. If English, write in English. Never mix languages. Button text, nav labels, section titles — everything must match the input language.

TECHNICAL REQUIREMENTS:
- Single complete HTML file, starting with <!DOCTYPE html>
- Include in <head>: <script src="https://cdn.tailwindcss.com"></script>
- Configure Tailwind colors in a <script> tag if needed for custom brand colors
- All icons must use emoji inside colored div containers — do not use SVG icons or external CDN images
- Hero section: use the hero image as a full-width background with a dark overlay for text readability
- The page must be fully responsive (mobile-first)
- Smooth scroll behavior on the page

SECTIONS TO BUILD (all 8, in this order):

1. NAVBAR
   - Logo (use product name, styled)
   - 2-3 nav links (Features, How it works, Pricing)
   - Sticky, with backdrop blur on scroll (use JS or CSS position:sticky)
   - CTA button aligned right
   - Product name and navigation links must have clear spacing between them.

2. HERO
   - Full-viewport-height section
   - Hero image as background with semi-transparent dark overlay
   - Headline: the core benefit, punchy, max 10 words
   - Sub-headline: 1 clear sentence — who this is for and what they get
   - Two CTAs: primary (filled) and secondary (outlined/ghost)
   - Scroll indicator arrow at bottom

3. SOCIAL PROOF BAR
   - Light background strip
   - Either: 3-4 real-sounding stats (e.g. "4,200+ teams", "98% retention", "< 5 min setup")
   - Or: "Trusted by teams at [FakeCompany1], [FakeCompany2], [FakeCompany3]" — use plausible company names

4. PRODUCT STORY (Why it exists)
   - Section heading
   - 2-3 paragraphs of flowing prose
   - Para 1: open by naming the exact frustration {$data['target_audience']} faces every day
   - Para 2: introduce how {$data['product_name']} approaches this differently
   - Para 3: paint the "after" picture — what their work/life looks like now

5. BENEFITS (3 items)
   - "What you actually get" framing
   - Each benefit MUST have a large emoji icon + bold title (outcome-focused) + 2-sentence description
   - Layout: 3-column grid on desktop, stacked on mobile
   - Each emoji MUST be inside: <div class="w-14 h-14 rounded-2xl bg-violet-100 flex items-center justify-center"><span class="text-2xl">🎯</span></div> — alternate bg colors (bg-violet-100, bg-indigo-100, bg-purple-100) across the 3 items. Choose emoji that clearly match each benefit meaning.
   - WARNING: Icon containers smaller than w-14 h-14 or emoji smaller than text-2xl are NOT acceptable. Use the EXACT div pattern specified.

6. FEATURES BREAKDOWN
   - Grid of feature cards (2-3 columns)
   - Each card MUST have a large emoji icon + feature name + one-line description
   - Each emoji MUST be inside: <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center"><span class="text-2xl">⚡</span></div> — alternate bg colors (bg-blue-100, bg-violet-100, bg-indigo-100, bg-purple-100) across cards. Choose emoji that clearly match each feature meaning.
   - WARNING: Icon containers smaller than w-14 h-14 or emoji smaller than text-2xl are NOT acceptable. Use the EXACT div pattern specified.
   - Use the exact features provided: {$features}

7. TESTIMONIALS (3 real-sounding quotes)
   - Each testimonial: avatar (UI Avatars API), name, role + company, quote
   - Quotes: 2-3 sentences, specific and human-sounding
   - Include a detail that makes it believable (time saved, specific use case, etc.)
   - Inventor three different realistic names and roles relevant to {$data['target_audience']}

8. PRICING
   - Clean card layout
   - Price: {$data['price']}
   - List what's included (derive from features + description)
   - Primary CTA button
   - Risk-reducer line: money-back guarantee, free trial, or "cancel anytime" — pick what fits
   - Optional: a simple FAQ with 2-3 common objections answered briefly
   - You MUST render ALL pricing plans provided in the input data. Every plan must have a complete feature list and CTA button. Do not truncate or abbreviate any plan.

9. FINAL CTA BANNER
   - Full-width section, high contrast background
   - Headline different from the hero — focus on urgency or loss aversion
   - One urgency element (limited time, limited seats, or results-based statement)
   - One CTA button with distinct text from all previous CTAs

10. FOOTER
   - Product logo/name styled
   - Navigation links (Features, Pricing, FAQ, Contact)
   - Copyright with current year
   - Placeholder social media icon links (Twitter/X, LinkedIn, GitHub or relevant platforms)

Output ONLY the complete HTML file. Start with <!DOCTYPE html> immediately.
PROMPT;
    }

    private function styleGuide(string $template): string
    {
        if ($template === 'bold') {
            return <<<'STYLE_END'
Style: BOLD
- Background: #0a0a0a (near black)
- Text: white (#ffffff) and light gray (#a1a1aa)
- Accent color: #facc15 (yellow-400) for CTAs, highlights, borders
- Section backgrounds: alternate between #0a0a0a and #111111
- Cards: #1a1a1a background with 1px #2a2a2a border
- Headlines: very large, heavy font weight (font-black or font-extrabold)
- Buttons: yellow background (#facc15) with black text, sharp corners (rounded-md)
- Overall feel: bold, high-contrast, direct — like a premium course or event landing page
- Social proof bar: dark background with yellow accent text for the numbers
STYLE_END;
        }

        return <<<'STYLE_END'
Style: MODERN
- Background: white (#ffffff) and very light gray (#f8f9fa) for alternating sections
- Primary gradient: from #7c3aed (violet-600) to #4f46e5 (indigo-600) — use for CTAs and accents
- Text: dark gray (#111827) for headings, (#6b7280) for body
- Cards: white with subtle border (#e5e7eb) and soft shadow
- Buttons: gradient background (violet to indigo), white text, rounded-xl
- Headlines: medium-heavy weight (font-bold or font-extrabold), tight tracking
- Overall feel: clean, spacious, trustworthy — like a well-funded B2B SaaS
- Social proof bar: light gray background (#f3f4f6) with gradient-colored stat numbers
STYLE_END;
    }

    // ── Regeneration ──────────────────────────────────────────────────────

    /**
     * Refine an existing sales page based on user feedback.
     * Preserves the original hero image (no new Unsplash call).
     */
    public function regenerate(
        array  $originalData,
        string $originalHtml,
        string $heroImageUrl,
        string $feedback,
        string $template = 'modern'
    ): string {
        $response = retry(
            times: 2,
            callback: fn () => OpenAI::chat()->create([
                'model'       => 'gpt-4o',
                'temperature' => 0.6,
                'max_tokens'  => 16000,
                'messages'    => [
                    ['role' => 'system',    'content' => $this->systemPrompt()],
                    ['role' => 'user',      'content' => $this->buildPrompt($originalData, $heroImageUrl, $template)],
                    ['role' => 'assistant', 'content' => $originalHtml],
                    ['role' => 'user',      'content' => $this->buildRegeneratePrompt($feedback)],
                ],
            ]),
            sleepMilliseconds: fn (int $attempt) => $attempt * 5000,
            when: fn (\Throwable $e) => $this->isRetryableError($e),
        );

        $html = trim($response->choices[0]->message->content ?? '');

        if (empty($html)) {
            throw new \RuntimeException('OpenAI returned an empty response on regenerate.');
        }

        $html = preg_replace('/^```(?:html)?\s*/i', '', $html);
        $html = preg_replace('/\s*```$/', '', $html);

        return trim($html);
    }

    private function isRetryableError(\Throwable $e): bool
    {
        $message = $e->getMessage();

        return str_contains($message, '429')
            || str_contains($message, '503')
            || str_contains($message, 'cURL error 28')
            || str_contains($message, 'timed out');
    }

    private function buildRegeneratePrompt(string $feedback): string
    {
        return <<<PROMPT
Revise the sales page above based on this feedback:

"{$feedback}"

Apply ONLY the changes implied by the feedback. Keep everything else — structure, hero image URL, all sections, testimonials — identical unless the feedback specifically asks to change them.

Copywriting rules still apply: no clichés, stay specific, sound human.

Output ONLY the complete revised HTML file. Start with <!DOCTYPE html> immediately.
PROMPT;
    }
}
