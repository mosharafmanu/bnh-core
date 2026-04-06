# BNH Master Status

## Project Summary

This WordPress theme project completed its major migration and routing phase before frontend theme development.

The BensNaturalHealth main site runs on Shopify. WordPress powers the blog application only. On production that application is mounted under `/blog`, but on local this WordPress install is already the blog root. All code therefore treats the local WordPress root as the application root and does not hardcode `/blog`.

This file is the quickest project re-entry summary. For deeper context, use [PROJECT-BNH-BLOG-THEME.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-BLOG-THEME.md).

## What Was Completed

- unified legacy blog content into default WordPress `post`
- adopted hierarchical `health_topic` as the core taxonomy
- created and populated parent topics:
  - `prostate-health`
  - `diabetes-health`
  - `hormone-health`
  - `general-health`
- generated child terms from legacy taxonomies
- completed dry-run migration tooling
- completed real post migration tooling
- added safe batch migration for large local runs
- preserved migration traceability with debug metadata
- normalized/fixed child term slugs
- resolved child/post slug collisions with `-topic`
- added future collision guard for new post saves with `-article`
- implemented clean routing for parent archives, child archives, and single posts
- added strict parent validation so wrong-parent URLs 404
- added legacy SEO redirect layer
- cleaned legacy taxonomy assignments from migrated posts where appropriate
- hid legacy taxonomy UI and legacy CPT menus in admin
- added SEO/Yoast verification reporting utilities

## Final Decisions

### Content Model

- Use `post` only.
- Use `health_topic` as the main content taxonomy.
- Treat taxonomy structure as the source of truth.
- Use ACF for term metadata, not for structural relationships.

### Naming

- Legacy "Sexual Health" is intentionally renamed to "Hormone Health".
- All former `sexualhealth` and `sexual_categories` content maps to `hormone-health`.

### URL Architecture

- `/{parent}/` -> parent archive
- `/{parent}/{child}/` -> child archive
- `/{parent}/{post-slug}/` -> single post

### Routing Rules

- Child archive resolution happens before single-post fallback.
- A single post only resolves if it truly belongs to the requested top-level parent.
- Wrong-parent URLs must 404.

### Redirect Rules

- Old prostate single URLs redirect into `/prostate-health/...`
- Old sexual single URLs redirect into `/hormone-health/...`
- Old legacy taxonomy archive URLs redirect into the new child or parent archive URLs
- Collision-renamed child slugs are explicitly mapped during redirects

## What Is Working

- parent archive URLs
- child archive URLs
- single-post URLs
- strict parent routing
- collision-safe child archive strategy
- legacy redirect layer
- migrated content assignments in `health_topic`
- admin cleanup for legacy taxonomy and CPT UI
- SEO verification reporting

## What Was Intentionally Deferred

- deleting legacy taxonomies
- unregistering legacy custom post types
- removing all migration/debug utilities
- final Yoast manual inspection and signoff
- frontend/theme implementation

These were left in place intentionally to preserve rollback visibility and make final verification easier.

## Files That Matter Most

Core memory:

- [PROJECT-BNH-BLOG-THEME.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-BLOG-THEME.md)
- [PROJECT-BNH-MASTER-STATUS.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-MASTER-STATUS.md)

Bootstrap:

- [functions.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/functions.php)

Routing and SEO:

- [inc/routing/health-topic-permalinks.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/routing/health-topic-permalinks.php)
- [inc/routing/health-topic-redirects.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/routing/health-topic-redirects.php)
- [inc/seo/health-topic-seo-verification.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/seo/health-topic-seo-verification.php)

Migration and cleanup:

- [inc/migration/health-topic-migrate-posts.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-migrate-posts.php)
- [inc/migration/health-topic-cleanup-legacy-taxonomies.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-cleanup-legacy-taxonomies.php)
- [inc/admin/hide-legacy-taxonomy-ui.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/admin/hide-legacy-taxonomy-ui.php)

Historical step notes kept for audit trail:

- [PROJECT-BNH-STEP-URL-DATA-VALIDATION.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-URL-DATA-VALIDATION.md)
- [PROJECT-BNH-STEP-COLLISION-SAFE-URLS.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-COLLISION-SAFE-URLS.md)
- [PROJECT-BNH-STEP-STRICT-PARENT-ROUTING.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-STRICT-PARENT-ROUTING.md)
- [PROJECT-BNH-STEP-SEO-REDIRECTS.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-SEO-REDIRECTS.md)
- [PROJECT-BNH-STEP-YOAST-SEO-VERIFICATION.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-YOAST-SEO-VERIFICATION.md)

Those step files should be treated as milestone notes, not as the current single source of truth.

## Documentation Audit Notes

Main issues found during this audit:

- the original main project memory file still described early migration planning rather than the completed architecture
- some step files describe an intermediate state that was later resolved by subsequent implementation
- URL validation notes correctly identified the routing collision problem, but later files superseded the temporary conclusion
- the SEO verification step is documented as available and active, but final manual Yoast/canonical inspection still needs explicit signoff

These gaps were resolved by rewriting the main memory file and adding this summary file.

## Risks And Cautions

- Do not hardcode `/blog` in code or documentation assumptions.
- Be careful changing permalink, redirect, or canonical logic. They now depend on each other.
- Keep strict parent validation intact unless replaced with an equally safe routing strategy.
- Keep the collision guard intact unless the URL namespace changes.
- Do not remove legacy structures from the database until final cleanup is intentionally scheduled.

## Next Phase

The next phase is theme development.

Planned scope:

- build reusable theme architecture
- implement header navigation using the 4 parent `health_topic` terms
- implement child-topic navigation under the active parent
- build the homepage with Prostate Health active by default
- build parent archive templates
- build child archive templates
- build single post templates with topic-aware active state
- connect ACF term metadata into presentation

At this point, the project is documented well enough to begin theme development without re-investigating the migration architecture first.

## New Theme Carry-Forward Plan

The current theme has now been audited for new-theme carry-forward.

A dedicated copy-plan document exists:

- [PROJECT-BNH-NEW-THEME-COPY-PLAN.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-NEW-THEME-COPY-PLAN.md)

Only permanent architecture files should move into the new theme. The migration, debug, recount, slug-fix, and one-time cleanup utilities should not be copied forward by default.

## Architecture Moved to Site Plugin

Permanent routing, redirect, canonical, collision-prevention, and content-governance architecture is now intended to be owned by the `bnh-site-core` site plugin rather than the active theme.

This means the long-term URL and SEO-safe content model no longer depends on `bnh-core` remaining active. Theme changes can focus on presentation while the plugin preserves:

- final `health_topic` permalink logic
- child-first route resolution
- strict parent validation
- legacy redirect behavior
- collision-safe save-time slug handling
- structural canonical alignment
- legacy taxonomy and CPT admin cleanup

The theme should now consume plugin-exposed helper functions for topic-aware presentation instead of owning permanent site architecture directly.
