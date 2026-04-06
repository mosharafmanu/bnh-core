# BNH New Theme Copy Plan

## Overview

This document audits the current `bnh-blog` theme and identifies what should and should not move into the new custom theme.

The goal is to carry forward only the permanent production architecture. Migration utilities, debug tools, recount helpers, and one-time cleanup scripts should not be copied blindly into the new theme.

Important context:

- migration is complete
- routing is complete
- redirects are complete
- legacy admin UI cleanup is complete
- legacy structures still remain registered in the system for now
- `/blog` must not be hardcoded anywhere

## Copy Strategy

Build the new theme with a clean `functions.php` and selectively re-include only the runtime architecture that production still depends on.

Immediate principle:

- copy permanent frontend/runtime behavior
- optionally copy short-term QA helpers only if they are actively needed during launch verification
- do not copy one-time migration or inspection tools

## Required Files To Copy

### Runtime architecture

- [inc/routing/health-topic-permalinks.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/routing/health-topic-permalinks.php)
  - Classification: Required
  - Reason: This is the core permalink and routing layer for the final architecture. Production depends on it for:
    - `/{parent}/`
    - `/{parent}/{child}/`
    - `/{parent}/{post-slug}/`
    - strict parent validation
    - child-first routing
    - future slug collision guard on `save_post`
  - Dependencies: none outside WordPress core, but other files depend on its helpers.

- [inc/routing/health-topic-redirects.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/routing/health-topic-redirects.php)
  - Classification: Required
  - Reason: This is the permanent SEO redirect layer for old prostate URLs, old sexual URLs, and legacy taxonomy archive URLs.
  - Dependencies:
    - should be loaded after [inc/routing/health-topic-permalinks.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/routing/health-topic-permalinks.php)
    - uses helper functions such as `bnh_get_allowed_health_topic_parent_slugs()`, `bnh_get_health_topic_child_term_by_parent_and_slug()`, and `bnh_post_belongs_to_health_topic_parent()`

- [inc/admin/hide-legacy-taxonomy-ui.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/admin/hide-legacy-taxonomy-ui.php)
  - Classification: Required for the immediate new-theme launch
  - Reason: Legacy taxonomies and legacy CPTs still remain registered elsewhere in the system. This file hides their admin UI so editors primarily interact with `post` + `health_topic`.
  - Dependencies: none outside WordPress core.
  - Future note: this can later be removed if the legacy CPTs/taxonomies are actually unregistered at the system level.

## Optional Temporary Files To Copy

- [inc/seo/health-topic-seo-verification.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/seo/health-topic-seo-verification.php)
  - Classification: Optional / temporary
  - Reason: Useful during QA and launch verification for checking expected URLs, sitemap locations, and manual canonical validation instructions.
  - Dependencies: none critical, but it assumes the final routing layer is present.
  - Recommendation: copy only if you want the same admin-only verification report available during new-theme QA. Remove after final SEO signoff.

- [PROJECT-BNH-BLOG-THEME.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-BLOG-THEME.md)
  - Classification: Optional / recommended documentation
  - Reason: Current single source of truth for architecture, routing, redirects, migration state, and next-phase frontend requirements.
  - Dependencies: none.

- [PROJECT-BNH-MASTER-STATUS.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-MASTER-STATUS.md)
  - Classification: Optional / recommended documentation
  - Reason: Fast project re-entry summary for future engineering sessions.
  - Dependencies: none.

## Files Not To Copy

### Do not copy `functions.php` as-is

- [functions.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/functions.php)
  - Classification: Do not copy as-is
  - Reason: It includes the entire legacy theme bootstrap plus all temporary migration/debug utilities. The new theme should have a fresh `functions.php` and only include the permanent architecture files it actually needs.
  - Dependencies: many legacy theme includes unrelated to the migrated architecture.
  - Recommendation: rebuild `functions.php` cleanly, then selectively require the carry-forward files listed in this plan.

### Migration generators and one-time utilities

- [inc/migration/health-topic-term-generator.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-term-generator.php)
  - Classification: Do not copy
  - Reason: one-time term generation utility
  - Dependencies: standalone admin trigger only

- [inc/migration/health-topic-dry-run.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-dry-run.php)
  - Classification: Do not copy
  - Reason: migration inspection only
  - Dependencies: standalone report utility

- [inc/migration/health-topic-migrate-posts.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-migrate-posts.php)
  - Classification: Do not copy
  - Reason: one-time batch migration runner
  - Dependencies: standalone admin trigger only

- [inc/migration/health-topic-fix-child-slugs.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-fix-child-slugs.php)
  - Classification: Do not copy
  - Reason: one-time slug normalization
  - Dependencies: standalone admin trigger only

- [inc/migration/health-topic-fix-conflict-slugs.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-fix-conflict-slugs.php)
  - Classification: Do not copy
  - Reason: one-time generic conflict fix
  - Dependencies: standalone admin trigger only

- [inc/migration/health-topic-fix-routing-collision-slugs.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-fix-routing-collision-slugs.php)
  - Classification: Do not copy
  - Reason: one-time collision-safe child slug renaming
  - Dependencies: standalone admin trigger only

- [inc/migration/health-topic-cleanup-legacy-taxonomies.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-cleanup-legacy-taxonomies.php)
  - Classification: Do not copy
  - Reason: one-time legacy assignment cleanup already completed
  - Dependencies: standalone admin trigger only

### Debug and verification utilities not needed in the permanent theme

- [inc/migration/health-topic-debug-parent-verification.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-debug-parent-verification.php)
  - Classification: Do not copy
  - Reason: migration verification report only
  - Dependencies: standalone admin trigger only

- [inc/migration/health-topic-debug-single-post.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-debug-single-post.php)
  - Classification: Do not copy
  - Reason: ad hoc post inspection utility only
  - Dependencies: standalone admin trigger only

- [inc/migration/health-topic-recount-terms.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-recount-terms.php)
  - Classification: Do not copy
  - Reason: recount helper only; not part of runtime architecture
  - Dependencies: standalone admin trigger only

### Historical markdown step notes

- [PROJECT-BNH-STEP-URL-DATA-VALIDATION.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-URL-DATA-VALIDATION.md)
  - Classification: Do not copy by default
  - Reason: historical milestone note; useful as archive, not runtime or primary documentation

- [PROJECT-BNH-STEP-COLLISION-SAFE-URLS.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-COLLISION-SAFE-URLS.md)
  - Classification: Do not copy by default
  - Reason: historical implementation note

- [PROJECT-BNH-STEP-STRICT-PARENT-ROUTING.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-STRICT-PARENT-ROUTING.md)
  - Classification: Do not copy by default
  - Reason: historical implementation note

- [PROJECT-BNH-STEP-SEO-REDIRECTS.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-SEO-REDIRECTS.md)
  - Classification: Do not copy by default
  - Reason: historical implementation note

- [PROJECT-BNH-STEP-YOAST-SEO-VERIFICATION.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-YOAST-SEO-VERIFICATION.md)
  - Classification: Do not copy by default
  - Reason: historical implementation note

## Required `functions.php` Includes For The New Theme

The new theme should not reuse the current `functions.php` wholesale.

Minimum architecture includes that should exist in the new theme bootstrap:

```php
$bnh_health_topic_permalinks = get_template_directory() . '/inc/routing/health-topic-permalinks.php';
if ( file_exists( $bnh_health_topic_permalinks ) ) {
	require $bnh_health_topic_permalinks;
}

$bnh_health_topic_redirects = get_template_directory() . '/inc/routing/health-topic-redirects.php';
if ( file_exists( $bnh_health_topic_redirects ) ) {
	require $bnh_health_topic_redirects;
}

$bnh_hide_legacy_taxonomy_ui = get_template_directory() . '/inc/admin/hide-legacy-taxonomy-ui.php';
if ( file_exists( $bnh_hide_legacy_taxonomy_ui ) ) {
	require $bnh_hide_legacy_taxonomy_ui;
}
```

Optional temporary QA include:

```php
$bnh_health_topic_seo_verification = get_template_directory() . '/inc/seo/health-topic-seo-verification.php';
if ( file_exists( $bnh_health_topic_seo_verification ) ) {
	require $bnh_health_topic_seo_verification;
}
```

Important load-order note:

- load `health-topic-permalinks.php` before `health-topic-redirects.php`
- redirects use helper functions defined in the permalink/routing file

## Notes About Dependencies

- `health-topic-permalinks.php` is the primary runtime architecture file.
- `health-topic-redirects.php` depends on helper functions from the routing file.
- `hide-legacy-taxonomy-ui.php` is independent and can be removed later if legacy CPTs/taxonomies are fully retired.
- `health-topic-seo-verification.php` is independent and safe to omit from production once QA is complete.

## What Could Later Move To A Plugin

These behaviors are architectural rather than visual, so they could eventually live in a small site plugin instead of the theme:

- `health-topic-permalinks.php`
- `health-topic-redirects.php`
- `hide-legacy-taxonomy-ui.php`

Reason:

- they define URL behavior, redirect behavior, and admin behavior
- they are not specific to template rendering
- moving them to a plugin would reduce coupling between theme presentation and site architecture

That said, they can remain in the new theme initially if that is the fastest clean migration path.

## Recommended Next Step

Start the new theme with:

1. a clean `functions.php`
2. a fresh `inc/` structure
3. only the permanent runtime architecture files carried forward
4. optional SEO verification helper included only during QA
5. no migration/debug layer copied into the new theme

This means the new theme can start cleanly without inheriting the migration toolbelt, while still preserving the routing, redirect, and admin behavior the production site depends on.
