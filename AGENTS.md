# BNH Core Development Rules

## Architecture

- `bnh-core` is the presentation/theme layer.
- `bnh-site-core` is the site architecture plugin.
- Routing, redirects, structural taxonomy logic, collision handling, canonical enforcement, and content-model rules belong to the plugin.
- The theme must consume plugin public helper functions instead of duplicating those rules.

## Development Rules

- Build all new theme work inside `bnh-core`.
- Do not modify `bnh-blog` unless explicitly instructed.
- Do not recreate routing logic inside the theme.
- Do not recreate redirect logic inside the theme.
- Do not duplicate parent/child `health_topic` resolution in the theme.
- Do not hardcode `/blog`.
- If plugin helpers exist, use them instead of reimplementing logic.

## Public Plugin Helper Functions

- `bnh_get_post_health_topic_parent_term()`
  Use this when the theme needs the canonical top-level topic term for a post.

- `bnh_get_post_health_topic_parent_slug()`
  Use this when the theme only needs the parent topic slug for active states or comparisons.

- `bnh_get_health_topic_term_url()`
  Use this when generating canonical links to parent or child `health_topic` archives.

- `bnh_get_post_health_topic_permalink()`
  Use this when generating the final topic-aware permalink for a post.

- `bnh_post_belongs_to_health_topic_parent()`
  Use this when templates need to validate whether a post belongs to a specific parent topic.

- `bnh_get_health_topic_parent_term_by_slug()`
  Use this when the theme needs a known parent topic term object from a slug.

- `bnh_get_health_topic_child_term_by_parent_and_slug()`
  Use this when the theme needs to resolve a child topic under a specific parent.

## Theme Usage Guidance

- Use plugin helpers for header active state.
- Use plugin helpers for child navigation state.
- Use plugin helpers for breadcrumbs.
- Use plugin helpers for topic-aware archive and post links.
- Use plugin helpers for validating topic context in templates.
- Use plugin helpers to avoid duplicated structural logic.

## Safe Usage

- Use `function_exists()` checks in theme code before calling plugin helper functions.
- Prefer failing safely in templates if the plugin is inactive rather than rebuilding structural logic locally.

## Do Not Do This

- Do not hardcode parent slugs inside template logic when plugin helpers can provide the answer.
- Do not rebuild parent detection manually from raw terms if a plugin helper exists.
- Do not copy architecture code from the plugin into the theme.
- Do not place SEO or routing rules in template files.
