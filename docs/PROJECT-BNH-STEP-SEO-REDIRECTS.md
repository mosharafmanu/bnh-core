# SEO Redirect Layer

## What Old URL Patterns Are Redirected

The final redirect layer handles only URL patterns that changed during the migration to `post` + `health_topic`.

Redirected patterns:

- old prostate singles:
  - `/{post-slug}/`
  - target: `/prostate-health/{post-slug}/`
- old sexual singles:
  - `/sexual-health/{post-slug}/`
  - target: `/hormone-health/{post-slug}/`
- old taxonomy archive bases:
  - `/category/{term-slug}/`
  - `/diabetes-categories/{term-slug}/`
  - `/sexual-categories/{term-slug}/`
  - `/generalhealth-categories/{term-slug}/`

## What New Targets Are Used

New targets are always built relative to the WordPress site root:

- `/{parent}/`
- `/{parent}/{child}/`
- `/{parent}/{post-slug}/`

This means `/blog` is not hardcoded. Production will naturally prepend `/blog` because of the real mount path of the application.

## Why `/blog` Is Not Hardcoded

The local WordPress install is already the blog application root.

If `/blog` were hardcoded into redirects:

- local URLs would be wrong
- routing would become environment-specific
- production behavior would depend on duplicated path assumptions

Using `home_url()` keeps redirects environment-safe.

## Collision-Renamed Child Terms

Some child terms were renamed with `-topic` to avoid collisions with post slugs.

The redirect layer includes an explicit mapping for those renamed terms so old taxonomy archive URLs still land on the correct new child archive.

Examples:

- `/category/bph/` -> `/prostate-health/bph-topic/`
- `/diabetes-categories/prediabetes/` -> `/diabetes-health/prediabetes-topic/`
- `/sexual-categories/sex-and-relationships/` -> `/hormone-health/sex-and-relationships-topic/`
- `/diabetes-categories/co/` -> `/diabetes-health/co/`

If no matching child term exists, the redirect falls back to the parent archive.

## Redirect Safety Rules

- only redirect when an old pattern is truly matched
- only redirect top-level prostate singles when the matched post actually belongs to `prostate-health`
- only redirect old sexual singles when the matched post actually belongs to `hormone-health`
- do not redirect unchanged current URLs such as `/diabetes-health/{post-slug}/`
- do not redirect admin, REST, AJAX, or feed requests

## Local Testing

Example local URLs to test:

- old prostate single:
  - `/bph/`
- old sexual single:
  - `/sexual-health/erectile-dysfunction/`
- old taxonomy archives:
  - `/category/bph/`
  - `/diabetes-categories/prediabetes/`
  - `/sexual-categories/sex-and-relationships/`

Expected new targets:

- `/prostate-health/bph/`
- `/hormone-health/erectile-dysfunction/`
- `/prostate-health/bph-topic/`
- `/diabetes-health/prediabetes-topic/`
- `/hormone-health/sex-and-relationships-topic/`

## How To Verify 301s

- open the old URL in the browser and confirm it lands on the new canonical URL
- use browser DevTools Network tab to verify the response code is `301`
- use `?bnh_debug_redirect=1` on an old URL to inspect which redirect rule matched and what target was selected

## No Rewrite Flush Needed

This redirect layer runs in `template_redirect`.

Because it does not add or change rewrite rules, no permalink flush is required just for this redirect module.

## Later Yoast Verification

Still verify later:

- canonical tags on redirected destinations
- XML sitemap URLs after final production mount behavior is confirmed
- whether old taxonomy archives should remain indexable or fully redirect-only in production
