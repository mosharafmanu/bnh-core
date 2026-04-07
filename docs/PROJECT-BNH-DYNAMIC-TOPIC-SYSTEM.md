# Dynamic Topic Navigation & Content System

## Overview

This document explains how the dynamic health topic navigation and content loading system is implemented in `bnh-core`.

The system follows the project rule that permanent architecture stays in `bnh-site-core`, while the theme handles:

- topic-aware rendering
- shared section output
- child-topic interaction
- AJAX / REST-powered content swaps

Parent navigation remains server-rendered and URL-based.
Child navigation is progressively enhanced with JavaScript.

## Behavior Summary

### Primary Navigation

- Parent `health_topic` links are normal links
- Clicking a parent topic performs a normal page transition
- The URL changes normally

### Secondary Navigation

- Child `health_topic` links render valid `href` values
- JavaScript intercepts those clicks
- Clicking a child topic does **not** change the URL
- The page updates only the topic content areas

### Dynamic Content Areas

The shared topic templates render:

- Featured Article
- Latest Articles
- Featured Research placeholder
- Community placeholder

### Heading Hierarchy

- Use one `<h1>` per page
- Homepage topic hub uses the active parent topic as the page-level heading
- Homepage `<h1>` uses only the active parent topic name
- Topic archive pages use the queried topic term name as the page-level `<h1>`
- Topic hub section titles use `<h2>`
- Article/card titles inside those sections use `<h3>`
- Topic navigation labels are not headings

### Pagination

- Latest Articles pagination is AJAX/REST-driven
- It does not perform a full page reload
- It updates only the Latest Articles block
- The Featured Article block is left unchanged

## Files Created

### Core topic-content logic

- `inc/helper-functions/topic-content.php`
- `inc/helper-functions/health-topic-rest.php`
- `assets/js/topic-navigation.js`

### Section partials

- `template-parts/sections/topic-featured-article.php`
- `template-parts/sections/topic-latest-articles.php`
- `template-parts/sections/topic-featured-research.php`
- `template-parts/sections/topic-community.php`

### Template support

- `author.php`

## Files Modified

### Context / navigation / bootstrap

- `functions.php`
- `inc/helper-functions/health-topic-context.php`
- `template-parts/sections/topic-child-nav.php`

### Shared template shells

- `front-page.php`
- `taxonomy-health_topic.php`

### Later refinement

- `template-parts/sections/topic-latest-articles.php`
- `docs/PROJECT-BNH-CHILD-NAV-FILTER.md`

## How It Works

### 1. Context Resolution

`inc/helper-functions/health-topic-context.php`

This file resolves the active topic state used by header navigation and shared templates.

It builds:

- `active_parent`
- `active_child`
- `parent_terms`
- `child_terms`
- query-state flags
- `paged`

Fallback behavior:

- homepage uses `prostate-health`
- search uses `prostate-health`
- author uses `prostate-health`

If the active parent has child terms, the default active child becomes the first child with published posts.

### 2. Parent Navigation

`template-parts/sections/topic-parent-nav.php`

- renders parent topic links
- uses plugin helper URLs
- remains normal server navigation

### 3. Child Navigation

`template-parts/sections/topic-child-nav.php`

- renders child topic links
- includes:
  - `data-parent-slug`
  - `data-child-slug`
- keeps valid fallback `href` values
- is enhanced by JavaScript for non-reloading interaction

### 4. Shared Topic Templates

`front-page.php` and `taxonomy-health_topic.php`

These two templates now use the same section partial flow:

- Featured Article
- Latest Articles
- Featured Research placeholder
- Community placeholder

This keeps homepage and taxonomy archive behavior aligned.

## Featured Article Rule

Defined in:

- `inc/helper-functions/topic-content.php`

Current rule:

- if the active child term has a manually selected `featured_article`, use it
- otherwise fall back to the most recent published post in the active child term

## Featured Research Rule

Defined in:

- `inc/helper-functions/topic-content.php`
- `template-parts/sections/topic-featured-research.php`

Current rule:

- if the active parent term has a manually selected `featured_research`, use it
- otherwise no featured research item is rendered for that parent

## Latest Articles Rule

Defined in:

- `inc/helper-functions/topic-content.php`
- `template-parts/sections/topic-latest-articles.php`

Current rule:

- latest published posts from the active child term
- paginated
- excludes the Featured Article using `post__not_in`

## REST Endpoint

Defined in:

- `inc/helper-functions/health-topic-rest.php`

Endpoint:

- `/wp-json/bnh-core/v1/topic-content`

The endpoint is intentionally narrow. It only:

- validates request parameters
- resolves parent/child context
- renders shared partial HTML
- returns the HTML response

## Response Behavior

### Child-nav click response

Returns:

- `featured_html`
- `latest_html`

### Latest pagination response

Returns:

- `latest_html` only

This is why pagination updates only the Latest Articles block and leaves Featured Article unchanged.

## JavaScript Layer

Defined in:

- `assets/js/topic-navigation.js`

Behavior:

- intercepts child nav clicks
- intercepts latest pagination clicks
- sends REST requests with:
  - `parent`
  - `child`
  - `paged`
  - `fragment`

Child click behavior:

- prevents default navigation
- fetches both `featured_html` and `latest_html`
- replaces both content blocks
- updates active child state

Pagination behavior:

- prevents default navigation
- fetches `latest_html` only
- replaces only the latest block

## Asset Loading

`functions.php`

The theme enqueues `assets/js/topic-navigation.js` through the existing theme asset-loading pattern.

It also injects the REST endpoint URL into:

- `window.bnhCoreTopicNavigation`

## Child Navigation Filtering

Related documentation:

- `docs/PROJECT-BNH-CHILD-NAV-FILTER.md`

Current rule:

- empty child terms are hidden from child navigation
- only child terms with at least one published post are shown
- default child selection uses the first visible child term

## Important Boundaries

The following are **not** handled in the theme:

- permalink architecture
- routing
- redirects
- canonical rules
- collision prevention

Those remain plugin-owned in `bnh-site-core`.

The theme only handles:

- display context
- topic-aware rendering
- partial output
- front-end interaction

Manual featured-content selection also stays in the theme:

- child-term `featured_article`
- parent-term `featured_research`

Reason:

- these are editorial and presentation-layer choices for the topic hub UI
- they do not define permanent routing, redirect, canonical, or taxonomy architecture
- if needed later, they can move to the plugin only if they become a permanent site-wide editorial model rather than theme behavior

## Testing Notes

Immediate behavior to verify:

1. Homepage renders parent nav, child nav, featured article, and latest articles
2. Parent archives render the same shared section flow
3. Child archives render the same shared section flow
4. Child-term clicks do not change the URL
5. Child-term clicks update featured + latest content
6. Latest pagination does not reload the page
7. Latest pagination updates only the latest block
8. Search and author pages still show fallback topic navigation
