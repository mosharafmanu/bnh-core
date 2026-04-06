# BNH Core Theme Structure

## Overview

`bnh-core` is the presentation layer for the site.

Permanent site architecture does **not** live in the theme. It is owned by the `bnh-site-core` plugin, including:

- routing
- redirects
- canonical logic
- collision handling
- structural taxonomy rules

The theme should consume plugin helpers for topic-aware decisions and focus on rendering UI, templates, and reusable sections.

## Root Files

- `functions.php`
  Theme bootstrap. Handles theme setup, supports, asset enqueues, and loading theme helper files.
- `header.php`
  Global site header and topic navigation entry point.
- `footer.php`
  Global site footer.
- `index.php`
  Generic fallback template.
- `page.php`
  Page template with ACF flexible content support.
- `single.php`
  Single post template.
- `archive.php`
  Generic archive template.
- `search.php`
  Search results template.
- `taxonomy-health_topic.php`
  Dedicated archive template for `health_topic`.
- `404.php`
  Not found template.
- `style.css`
  Theme stylesheet header plus base theme stylesheet.
- `style-rtl.css`
  RTL stylesheet.
- `AGENTS.md`
  Development rules for the theme/plugin boundary.

## `inc/`

The `inc/` directory contains theme-side support code.

### `inc/image-sizes.php`

Defines custom image sizes and related media settings.

### `inc/helper-functions/`

Reusable theme helper functions. These should support presentation and template rendering, not permanent site architecture.

Current helper files:

- `breadcrumb.php`
- `button-renderer.php`
- `flexible-content.php`
- `health-topic-context.php`
- `icon-renderer.php`
- `pagination.php`
- `post-utilities.php`
- `responsive-picture.php`
- `site-settings.php`
- `video-renderer.php`

Important notes:

- `health-topic-context.php` is a theme-side context builder for navigation and templates.
- `flexible-content.php` resolves ACF layouts to section templates.
- Topic-related structural decisions must still come from `bnh-site-core` helper functions.

### `inc/components/`

Lower-level reusable PHP components.

Current files:

- `inc/components/blog/blog-card.php`
- `inc/components/video/video-autoplay-controls.php`

## `template-parts/`

Reusable view partials used by templates.

### Content partials

- `template-parts/content.php`
- `template-parts/content-page.php`
- `template-parts/content-search.php`
- `template-parts/content-none.php`

### Section partials

- `template-parts/sections/topic-parent-nav.php`
- `template-parts/sections/topic-child-nav.php`

`template-parts/sections/` is the intended location for ACF flexible content section templates.

## `assets/`

Frontend CSS, JS, and SVG assets.

### `assets/css/`

Current CSS files:

- `bnh-core-theme.css`
- `bnh-core-form.css`
- `bnh-core-slick-custom.css`
- `slick.css`
- `spacer.css`
- `utilities.css`
- `video-behaviors.css`
- `video-popup.css`

### `assets/js/`

Current JS files:

- `scripts.js`
- `hamburger-menu.js`
- `bnh-core-carousels.js`
- `slick.js`
- `jquery.mb.vimeo_player.min.js`
- `video-behaviors.js`
- `video-popup.js`

Notes:

- `bnh-core-carousels.js` currently contains only one minimal example carousel initializer.
- Assets are enqueued from `functions.php` using `bnh-core-*` handles and `filemtime()` versioning.

### `assets/svgs/`

SVG assets and inline SVG partial support.

## `acf-json/`

Local ACF field group JSON storage.

Important files include:

- `group_flexible_content.json`
- `group_page_settings.json`
- `group_site_settings.json`
- `group_blog_options.json`

Current status:

- `group_flexible_content.json` has been reduced to one example layout: `hero_section`

## `docs/`

Project memory and development documentation.

Important files:

- `PROJECT-BNH-MASTER-STATUS.md`
- `PROJECT-BNH-THEME-TODO.md`
- migration/routing/SEO history files copied from the old project

This directory should be treated as internal project memory.

## Asset Loading

`functions.php` currently handles:

- theme setup and supports
- menu registration
- sidebar registration
- CSS enqueueing from `assets/css/`
- JS enqueueing from `assets/js/`
- helper file loading from `inc/helper-functions/`

## Theme–Plugin Boundary

### Belongs in `bnh-site-core`

- permalink and routing logic
- redirects
- canonical logic
- collision prevention
- structural taxonomy rules

### Belongs in `bnh-core`

- templates
- header/footer rendering
- navigation markup
- section templates
- ACF flexible content rendering
- asset loading
- presentation helpers

## Development Rules

- Do not recreate routing logic inside the theme.
- Do not recreate redirect logic inside the theme.
- Do not duplicate `health_topic` structural resolution in the theme.
- Do not hardcode `/blog`.
- Use plugin public helper functions for topic-aware decisions.
- Build new reusable content sections in `template-parts/sections/`.
- Keep permanent architecture in `bnh-site-core` and presentation in `bnh-core`.
