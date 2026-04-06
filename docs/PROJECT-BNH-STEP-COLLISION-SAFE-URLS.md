# Collision-Safe Health Topic URLs

## Problem

The final clean URL architecture requires:

- `/{parent}/` for parent archives
- `/{parent}/{child}/` for child archives
- `/{parent}/{post}/` for single posts

This creates a routing risk when a child term slug and a post slug are identical under the same parent topic. In that case, `/{parent}/{segment}/` is ambiguous.

Real collisions were found in live local data during URL validation.

## Why Clean URLs Were Kept

The clean two-segment structure is still the preferred architecture because:

- it keeps the topic hierarchy readable
- it matches the editorial structure of the migrated blog
- it avoids adding an extra archive base such as `/topic/`
- it keeps parent topics visible in single-post URLs

## Why Only Child Terms Were Renamed

Published post slugs are part of the public article layer and should remain stable unless there is a future save-time collision.

Child terms are lower-risk to rename because:

- term IDs and relationships remain intact
- post-term assignments are preserved
- archive slugs can be made collision-safe without altering article slugs

Only the confirmed colliding child term slugs are renamed, and the new rule is to append `-topic`.

Examples:

- `bph` -> `bph-topic`
- `prostate-cancer` -> `prostate-cancer-topic`
- `prediabetes` -> `prediabetes-topic`
- `erectile-dysfunction` -> `erectile-dysfunction-topic`

## Future Guard Logic

A save-time guard now runs on `save_post` for `post`.

Behavior:

- resolve the post's top-level `health_topic` parent
- load all child terms under that parent
- if the post slug matches a child term slug under that parent
- automatically change the post slug by appending `-article`

Example:

- `bph` -> `bph-article`

This prevents future collisions without blocking editors.

## Final URL Structure

- `/{parent}/` -> parent archive
- `/{parent}/{child}/` -> child archive
- `/{parent}/{post}/` -> single post

Routing rule for `/{parent}/{segment}/`:

1. If `{segment}` matches a child term under `{parent}`, load the child archive.
2. Otherwise, resolve it as a single post constrained to the requested parent.

## Collision Renames

Planned collision-safe child slug renames:

- `prostate-health`:
  - `bph` -> `bph-topic`
  - `prostate-cancer` -> `prostate-cancer-topic`
  - `prostate-medication` -> `prostate-medication-topic`
- `diabetes-health`:
  - `diabetes-complications` -> `diabetes-complications-topic`
  - `diabetes-management` -> `diabetes-management-topic`
  - `diabetes-supplements` -> `diabetes-supplements-topic`
  - `prediabetes` -> `prediabetes-topic`
  - `type-1-diabetes` -> `type-1-diabetes-topic`
  - `type-2-diabetes` -> `type-2-diabetes-topic`
- `hormone-health`:
  - `erectile-dysfunction` -> `erectile-dysfunction-topic`
  - `low-testosterone` -> `low-testosterone-topic`
  - `sex-and-relationships` -> `sex-and-relationships-topic`

## Test URLs

After renaming collision terms and flushing permalinks, test:

- `/prostate-health/`
- `/diabetes-health/`
- `/hormone-health/`
- `/general-health/`
- `/prostate-health/bph-topic/`
- `/prostate-health/prostate-cancer-topic/`
- `/diabetes-health/prediabetes-topic/`
- `/hormone-health/erectile-dysfunction-topic/`
- `/diabetes-health/sample-post/`
- `/prostate-health/sample-post/`

## Flush Rules

Do not flush rewrite rules on every request.

Flush once after deploying routing changes by either:

- visiting Settings > Permalinks and clicking Save Changes
- or using the temporary manual flush trigger already present in the theme
