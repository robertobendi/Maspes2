# BUILD_BRIEF — what "done" means

You are building the public face of **robertobendi/Maspes2** (modeled after https://maspesfiori.it/). Target: GitHub
Pages, served as a static export of this PebbleStack site.

## Hard requirements

1. **Single theme dir** — work inside `templates/theme/default/`. No new theme dirs.
2. **Inline CSS in layout.twig** — keep PebbleStack's pattern. Google Fonts via `<link>` in `<head>`. Palette + type scale from BRIEF.md.
3. **No JavaScript frameworks**. Vanilla `<script>` only for tiny interactions (mobile menu). Site must render meaningfully with JS disabled.
4. **Discoverable nav** — every public page must be linked from header or footer. The crawler is link-driven; orphan pages won't export.
5. **Collections from BRIEF.md § 3 · Plan** — implement them in `config/collections.php`.
6. **No admin work** — never edit `templates/admin/`.
7. **Real content** — every page must have real, branded copy in BRIEF.md's voice. No lorem ipsum. No "Coming soon".

## Imagery — REQUIRED, NOT OPTIONAL

Three sources, in priority:

### 1. Real photographs (Wikimedia Commons, no auth)

    bash scripts/fetch-image.sh "search query" [count] [dest_dir]

Examples — be specific and visual:

    bash scripts/fetch-image.sh "florist shop interior wood" 3 uploads/
    bash scripts/fetch-image.sh "rose bouquet pink white" 4 uploads/heroes

The script saves to `dest_dir` and prints paths. Reference as `/uploads/<name>`.
Generic queries return generic results. Include 2–3 modifiers (color, mood,
material, setting).

### 2. source/images/

Anything pulled from the original site. Move into `uploads/`.

### 3. Inline SVG illustrations

For spot art, icons, decorative dividers.

### Hard rule

Every page must use REAL imagery somewhere — at minimum a hero image.
Color-block fallbacks are ONLY acceptable for small accent areas.

## Forms

Per BRIEF.md § 3 · Plan's "Forms" decision. If Formspree placeholder, leave
an HTML comment next to the action telling the user to swap it.

## Build + verify command

Run the export with EXACTLY these credentials (Bismuth tracks them so the
user can log into the local admin later):

    ADMIN_EMAIL="admin@bismuth.local" \
    ADMIN_PASSWORD="bis-KlHpy_eeU2AWWo" \
    ADMIN_NAME="Admin" \
    SITE_NAME="Maspes2" \
    bash scripts/export-static.sh

This boots `php -S`, runs the headless install with the creds above, and
mirrors the site to `docs/`. If it fails, fix the underlying issue and
re-run.

## Components — USE THESE BEFORE INVENTING

`COMPONENTS.md` in the repo root has known-good HTML patterns for: map,
address card, contact form, image gallery, FAQ, mobile menu, testimonial,
pricing table, hero with image, social links. **Use those patterns**
(adapt classes/copy to the brand) instead of inventing your own.

**Do NOT invent fake interactive widgets.** Specifically:

- No fake maps (drawn as SVG / canvas / divs). Use the OSM iframe pattern
  in COMPONENTS.md if coordinates are known, otherwise the address card.
- No fake charts or graphs with invented numbers.
- No fake calendars showing fake events.
- No fake audio/video players with no real media.
- No fake testimonials with made-up names — use what's in source/text.md.
- No fake "trusted by" logo strips.

When in doubt: REMOVE the section and find a simpler way to occupy the
real estate. Empty space + good typography > fake content.

## QA pass — DESKTOP + MOBILE, DO NOT SKIP

After `docs/index.html` exists, OPEN each `docs/*.html` and audit at BOTH
viewport sizes in your head: **1440px desktop** and **375px mobile**. If
the CSS doesn't have media queries handling both, fix it.

### Desktop (1440px)

1. **Contrast (WCAG AA)** — every text/bg pair. Body ≥ 4.5:1, large
   headings ≥ 3:1. Hunt color-on-color (button text matching bg, link
   too close to body bg, muted text disappearing into surface).
2. **Spacing consistency** — match BRIEF.md § 4 · Design "Spacing &
   rhythm". No section cramped vs others. No collapsed margins between
   adjacent blocks.
3. **Imagery present** — every page has at least one real image.
4. **Hierarchy** — one H1 per page, H2 for sections, H3 within. Type
   sizes follow the design scale.

### Mobile (375px width)

1. **Viewport meta** — `<meta name="viewport" content="width=device-width,
   initial-scale=1">` is present.
2. **No horizontal overflow** — at 375px width, nothing scrolls
   horizontally. Wide images use `max-width: 100%; height: auto`. Long
   words break with `overflow-wrap: anywhere` where needed.
3. **Touch targets ≥ 44×44px** — buttons, nav links, form inputs all
   meet the minimum.
4. **Gutters ≥ 16px** — body text doesn't touch the viewport edge.
5. **Mobile nav** — the desktop nav collapses into a working hamburger
   menu at narrow widths (use the COMPONENTS.md pattern).
6. **Type sizes adapt** — display/H1 should reduce on mobile (use
   `clamp()` or media-query overrides). 8rem headings don't fit on a
   phone.
7. **Forms are usable** — input height, label legibility, no zoom-on-focus
   (set `font-size: 16px` on inputs to prevent iOS auto-zoom).

For EACH issue found at EITHER viewport, EDIT the template, re-run the
export, re-audit. Loop until both audits produce no findings.

## Tone

Use BRIEF.md § 2 · Brand's voice. Don't invent product features the source
didn't have. BRIEF.md § 1 · Analysis "Hard facts to preserve" is
non-negotiable.
