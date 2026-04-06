# Yoast SEO Verification

## What Was Verified

The final verification step focuses on the URL system that now exists after migration:

- `/{parent}/`
- `/{parent}/{child}/`
- `/{parent}/{post-slug}/`

The verification utility reports:

- expected parent archive URLs
- sample child archive URLs
- sample single post permalinks
- expected Yoast sitemap URLs
- canonical inspection instructions
- redirect examples to test manually

## Final URL Structure

- `/{parent}/` for parent archives
- `/{parent}/{child}/` for child archives
- `/{parent}/{post-slug}/` for single posts

No `/blog` base is hardcoded in code. The local WordPress install is already the blog root, and production will naturally prepend `/blog` because of the real mount path.

## What To Check In Yoast Sitemap

Confirm that these URLs are available and sensible:

- sitemap index
- post sitemap
- page sitemap
- author sitemap
- `health_topic` sitemap if Yoast exposes it

Check that generated URLs use the final clean structure rather than legacy paths.

## What To Check In Canonical Tags

Manually inspect page source for:

- one parent archive
- one child archive
- one single post

Confirm:

- the canonical tag exists
- the canonical URL matches the final frontend URL exactly
- there are no obvious legacy taxonomy archive canonicals
- there are no missing or wrong parent segments

## Redirect Examples To Test

- old prostate single:
  - `/bph/`
- old sexual single:
  - `/sexual-health/erectile-dysfunction/`
- old taxonomy archive:
  - `/category/bph/`
  - `/diabetes-categories/prediabetes/`
  - `/sexual-categories/sex-and-relationships/`

Expected targets:

- `/prostate-health/bph/`
- `/hormone-health/erectile-dysfunction/`
- `/prostate-health/bph-topic/`
- `/diabetes-health/prediabetes-topic/`
- `/hormone-health/sex-and-relationships-topic/`

## What Remains After This Step

After SEO and Yoast verification is complete:

- the migration layer is effectively finalized
- the final URL and redirect system is in place
- theme development can begin on top of the stable content and routing architecture
