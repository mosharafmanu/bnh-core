# Project Memory: BNH Blog Theme

## Project Goal

This project is a custom WordPress blog rebuild for BensNaturalHealth.

The main BensNaturalHealth website runs on Shopify. WordPress powers only the blog application. On production, the blog lives under `/blog/`, but on local this WordPress install is already the blog app root. Because of that, `/blog` must not be hardcoded in permalink generation, rewrites, redirects, or canonical assumptions.

The migration and routing phase was completed before theme development so the future theme can be built on a clean, stable content model.

## Final Content Architecture

The legacy multi-post-type structure has been consolidated into:

- post type: `post`
- primary taxonomy: `health_topic`
- taxonomy type: hierarchical

Top-level `health_topic` parent terms:

- `prostate-health`
- `diabetes-health`
- `hormone-health`
- `general-health`

Important naming rule:

- Legacy "Sexual Health" was intentionally renamed to "Hormone Health".
- All legacy `sexualhealth` content and `sexual_categories` terms map to `hormone-health`.
- The new frontend should never show "Sexual Health".

Child terms now live under the correct parent terms inside `health_topic`. The taxonomy is the structural source of truth. ACF term fields are intended to enrich the taxonomy, not replace it.

## Final URL Structure

The finalized frontend URL structure is:

- `/{parent}/` -> parent archive
- `/{parent}/{child}/` -> child archive
- `/{parent}/{post-slug}/` -> single post

Examples:

- `/prostate-health/`
- `/diabetes-health/prediabetes-topic/`
- `/hormone-health/erectile-dysfunction/`

Rules:

- Single posts use only the top-level `health_topic` parent in the permalink.
- Child terms are never included in single-post permalinks.
- Child archives and single posts intentionally share `/{parent}/{segment}/`.
- `/blog` is not hardcoded anywhere in routing or permalink code.

## Routing Rules

Routing is implemented in a collision-safe way.

For `/{parent}/{segment}/`:

1. If `{segment}` matches a child `health_topic` term under `{parent}`, load the child archive.
2. Otherwise, try to resolve `{segment}` as a post slug.
3. A post is only allowed to resolve if it actually belongs to the requested top-level parent.
4. Wrong-parent URLs must 404.

Examples:

- `/prostate-health/bph-topic/` -> child archive
- `/prostate-health/bph/` -> single post
- `/prostate-health/prediabetes/` -> 404
- `/diabetes-health/bph/` -> 404

Collision handling:

- Real child/post slug collisions were found in migrated data.
- Conflicting child term slugs were renamed with `-topic`.
- A save-time guard auto-appends `-article` to future post slugs if they would collide with a child slug under the same parent.

## Redirect Rules

Legacy redirect behavior is implemented in a dedicated redirect layer.

Redirected legacy patterns:

- Old prostate single posts:
  - `/{post-slug}/` -> `/prostate-health/{post-slug}/`
  - only when the matched post truly belongs to `prostate-health`
- Old sexual single posts:
  - `/sexual-health/{post-slug}/` -> `/hormone-health/{post-slug}/`
  - only when the matched post truly belongs to `hormone-health`
- Old taxonomy archive URLs:
  - `/category/{term-slug}/`
  - `/diabetes-categories/{term-slug}/`
  - `/sexual-categories/{term-slug}/`
  - `/generalhealth-categories/{term-slug}/`

Legacy taxonomy archive redirects map to:

- the matching new child archive when a valid child exists
- the collision-renamed `-topic` child archive when applicable
- the parent archive when no child target exists

Redirect rules are frontend-only, use `301`, avoid loops, and do not hardcode `/blog`.

## Migration Status

Migration work is complete on the local migrated copy.

Completed migration steps:

- `health_topic` taxonomy created
- four parent terms created
- child terms generated from legacy taxonomies
- dry-run post migration built and executed
- real post migration built and executed
- batch migration support added for large local runs
- migration source metadata stored on migrated posts
- child term slugs normalized toward live-style slugs
- remaining generic conflict slugs adjusted safely
- routing collision slugs handled with `-topic`
- legacy taxonomy assignments cleaned from migrated posts where appropriate

Legacy-to-new migration mapping:

- `post` + `category` -> `prostate-health`
- `diabeteshealth` + `diabetes_categories` -> `diabetes-health`
- `sexualhealth` + `sexual_categories` -> `hormone-health`
- `generalhealth` + `generalhealth_categories` -> `general-health`

Migration debug metadata used on posts:

- `_bnh_migrated_from_post_type`
- `_bnh_migrated_legacy_taxonomy`
- `_bnh_migrated_at`

Important cleanup rule that was applied:

- If a migrated post had legacy terms, it received the mapped parent plus matching child terms in `health_topic`.
- If a migrated post had no legacy terms, it received only the mapped parent term.
- Legacy taxonomies were not deleted; only assignments on migrated posts were cleaned where needed.

## Cleanup Status

Legacy cleanup is partially complete by design.

Completed:

- legacy taxonomy assignments removed from migrated posts where appropriate
- `health_topic` term recount utility added
- single-post debug and parent verification utilities added for audit work

Not done intentionally:

- old taxonomies are still registered
- old custom post types are still registered
- no legacy data structures have been deleted yet

This preserves rollback/debug visibility while preventing editors from using the old model.

## Admin UI Status

Admin cleanup layers are in place to reduce confusion.

Hidden in admin:

- legacy custom taxonomies:
  - `diabetes_categories`
  - `sexual_categories`
  - `generalhealth_categories`
- their post edit metaboxes
- their list table columns
- related quick edit / bulk edit UI where applicable
- legacy CPT menu items:
  - `diabeteshealth`
  - `sexualhealth`
  - `generalhealth`

Still visible and active:

- normal `post`
- `health_topic`
- built-in `category`
- built-in `post_tag`

Important caution:

- built-in `category` has not been globally hidden because of legacy prostate history and possible future cleanup decisions.

## SEO / Yoast Status

SEO-related architecture is implemented and a verification utility exists.

Implemented:

- final permalink structure
- strict parent routing
- legacy redirect layer
- canonical and sitemap verification report utility

Current SEO verification scope:

- expected parent archive URLs
- expected child archive URLs
- sample single post permalinks
- sitemap locations
- manual canonical checks
- manual redirect checks

Status note:

- routing and redirects are complete
- final Yoast/canonical/sitemap verification is the last architecture check before theme development

## Important Implementation Files

Primary bootstrap:

- [functions.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/functions.php)

Migration utilities:

- [inc/migration/health-topic-term-generator.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-term-generator.php)
- [inc/migration/health-topic-dry-run.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-dry-run.php)
- [inc/migration/health-topic-migrate-posts.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-migrate-posts.php)
- [inc/migration/health-topic-fix-child-slugs.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-fix-child-slugs.php)
- [inc/migration/health-topic-fix-conflict-slugs.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-fix-conflict-slugs.php)
- [inc/migration/health-topic-fix-routing-collision-slugs.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-fix-routing-collision-slugs.php)
- [inc/migration/health-topic-cleanup-legacy-taxonomies.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-cleanup-legacy-taxonomies.php)
- [inc/migration/health-topic-debug-parent-verification.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-debug-parent-verification.php)
- [inc/migration/health-topic-debug-single-post.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-debug-single-post.php)
- [inc/migration/health-topic-recount-terms.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/migration/health-topic-recount-terms.php)

Routing and redirects:

- [inc/routing/health-topic-permalinks.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/routing/health-topic-permalinks.php)
- [inc/routing/health-topic-redirects.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/routing/health-topic-redirects.php)

Admin cleanup:

- [inc/admin/hide-legacy-taxonomy-ui.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/admin/hide-legacy-taxonomy-ui.php)

SEO verification:

- [inc/seo/health-topic-seo-verification.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/inc/seo/health-topic-seo-verification.php)

Historical step notes:

- [PROJECT-BNH-STEP-URL-DATA-VALIDATION.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-URL-DATA-VALIDATION.md)
- [PROJECT-BNH-STEP-COLLISION-SAFE-URLS.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-COLLISION-SAFE-URLS.md)
- [PROJECT-BNH-STEP-STRICT-PARENT-ROUTING.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-STRICT-PARENT-ROUTING.md)
- [PROJECT-BNH-STEP-SEO-REDIRECTS.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-SEO-REDIRECTS.md)
- [PROJECT-BNH-STEP-YOAST-SEO-VERIFICATION.md](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-blog/PROJECT-BNH-STEP-YOAST-SEO-VERIFICATION.md)

## Risks And Future Cautions

- Do not hardcode `/blog` into code. Production mount path should be handled by `home_url()` and the real site structure.
- Do not unregister or delete old taxonomies/CPTs until final confidence and rollback needs are settled.
- Do not remove the strict parent validation from routing. Without it, wrong-parent URLs can resolve incorrectly.
- Do not remove the collision guard unless child/post namespace rules are replaced with another safe strategy.
- Be careful when editing permalink logic, redirects, canonical handling, or Yoast settings. These systems now depend on each other.
- Some step-by-step markdown files are historical snapshots. Use this file and `PROJECT-BNH-MASTER-STATUS.md` as the primary sources of truth.

## Next Phase

The next phase is theme development on top of the finalized content and routing architecture.

Priority frontend work:

- build reusable theme architecture and shared helpers
- implement the header with the four parent health topics
- implement child-topic navigation under the active parent
- build the custom homepage with Prostate Health active by default
- build the parent archive template
- build the child archive template
- build the single post template with topic-aware active state
- wire term-based ACF metadata into the new templates where needed

Theme development should assume the migration layer, routing rules, and redirect strategy are already established and should not be re-litigated unless a real bug is found.
