# Strict Parent Routing

## Bug Found

The collision-safe URL layer still allowed wrong-parent URLs to resolve in some cases.

Examples of invalid URLs that were loading before the fix:

- `/prostate-health/prediabetes/`
- `/diabetes-health/bph/`

These are incorrect because:

- `prediabetes` belongs to `diabetes-health`
- `bph` belongs to `prostate-health`

## Why This Was Dangerous

If `/{parent}/{segment}/` resolves across parent boundaries, the routing layer can serve:

- the wrong single post under the wrong topic URL
- incorrect canonical paths
- confusing breadcrumbs and internal links
- inconsistent SEO behavior

That breaks the core architecture where the top-level parent topic is part of the permanent URL contract.

## Strict Parent Validation

The routing layer now validates parent ownership explicitly.

For `/{parent}/{segment}/`:

1. Check whether `{segment}` is a child `health_topic` term directly under `{parent}`.
   If yes, load the child archive.
2. Otherwise, try to resolve `{segment}` as a `post` slug.
3. If a post is found, validate that the post truly belongs to `{parent}` by inspecting assigned `health_topic` terms.
4. If the post does not belong to the requested parent, return 404.

## Valid URL Examples

- `/prostate-health/bph/`
- `/prostate-health/bph-topic/`
- `/diabetes-health/prediabetes/`
- `/diabetes-health/prediabetes-topic/`

## Invalid URL Examples

- `/prostate-health/prediabetes/`
- `/diabetes-health/bph/`
- `/hormone-health/type-2-diabetes/`

These should now return 404.

## Files Updated

- `inc/routing/health-topic-permalinks.php`

## Test After Flushing Rewrites

Test these locally:

- `/prostate-health/`
- `/prostate-health/bph/`
- `/prostate-health/bph-topic/`
- `/prostate-health/prediabetes/`
- `/diabetes-health/prediabetes/`
- `/diabetes-health/prediabetes-topic/`
- `/diabetes-health/bph/`

Optional routing debug:

- append `?bnh_debug_routing=1`
- the page source will include an HTML comment showing the matched branch
