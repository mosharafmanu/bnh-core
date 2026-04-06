# Dynamic Health Topic Navigation & Content System Plan

This implementation plan outlines the structural integration of a two-level, topic-aware navigation and dynamic content loading strategy into the `bnh-core` theme. This strictly follows project constraints: architecture logic remains in `bnh-site-core` while presentation and interactivity live here.

## 1. Responsibility Split & Structural Recommendations

**`header.php` & Nav Partials**
- Strictly limited to global wrappers, header UI, and calling `bnh_get_health_topic_context()` to power the navigation. No query logic for "Featured/Latest" belongs here.
- `template-parts/sections/topic-parent-nav.php`: Generates global parent links. These represent hard page transitions (server reloads), outputting standard permalinks.
- `template-parts/sections/topic-child-nav.php`: Outputs child links. These will have `data-parent-slug` and `data-child-slug` injected to aid the JS interaction layer, but will still output valid `href` values for SEO fallback.

**`inc/helper-functions/health-topic-context.php`**
- Centralized state resolution logic using WP query states.
- If context is weak (e.g., `is_search()`, `is_author()`, or standard pages), applies the exact **`prostate-health`** fallback for the `active_parent`.
- Auto-resolves the *first* child term for a parent if `active_child` is absent (e.g., on `front-page.php` or parent archives) to establish a complete default state for initial renders.

**`front-page.php` & `taxonomy-health_topic.php` (Template Reuse)**
- Both act as the primary template shells handling the initial, SEO-safe server-render.
- They will share the exact same rendering logic, feeding the resolved `$bnh_context` into reusable section partials placed in `template-parts/sections/`.

**Section Partials (`template-parts/sections/`)**
- `topic-featured-article.php`: Uses `$args['context']` to query and render the single featured article for the active child.
- `topic-latest-articles.php`: Uses `$args['context']` to query and render the paginated list of latest articles for the active child.
- `topic-featured-research.php` & `topic-community.php`: Renders parent-level components that remain static across child-term swaps.

**`single.php`**
- Resolves context dynamically based on the current post's assigned parent (and child) topics. This powers the header navigation correctly, while the body focuses on the main single post content.

**`search.php` & `author.php`**
- Retrieve context purely to populate the two-level header navigation (using the `prostate-health` fallback). 
- Their `<main>` `<article>` loop structure remains standard native WP behavior, unaffected by the dynamic topic content sections.

**JS Interaction (`assets/js/topic-navigation.js`)**
- Written in pure, modern **Vanilla JS**.
- Intercepts clicks on `.topic-child-nav__link` and `.topic-latest-articles .pagination a`.
- Calls `e.preventDefault()`. Note: Child-nav state is intentionally ephemeral and will reset if the page is hard-refreshed.
- Dispatches AJAX fetch calls to the custom REST API, providing current `parent`, `child`, and `paged` variables to preserve state.
- Updates the DOM's `.is-active` states.
- Swaps out the HTML contents for ONLY the "Featured Article" and "Latest Articles" containers without a full page reload or URL mutation.

## 2. The Async Approach: Narrow Custom REST Endpoint

> [!TIP]
> **Recommendation:** Use a custom REST API Endpoint instead of `admin-ajax.php`.

To ensure performant, decoupled data fetching, we will register a custom route:
`/wp-json/bnh-core/v1/topic-content?parent={slug}&child={slug}&paged={int}`

- **Implementation Location:** `inc/helper-functions/health-topic-rest.php`
- **Behavior:** The endpoint will read the `parent`, `child`, and `paged` parameters to recreate the proper WP context. It will use `ob_start()`, call `get_template_part()` for the featured and latest article partials, and return a JSON object containing `{ "featured_html": "...", "latest_html": "..." }`.
- **Pagination details:** Latest articles pagination must preserve the current active parent and child context (by sending them as parameters). Upon a pagination click, the endpoint will return updated HTML *only* for the `latest_html` block, leaving the Featured Article intact.

## 3. Implementation Order

1. **Context Expansion:** Enhance `inc/helper-functions/health-topic-context.php` to handle `is_search()`, `is_author()`, and `is_front_page()` with the `prostate-health` + first-child fallbacks.
2. **Navigation Enhancements:** Add `data-parent-slug` and `data-child-slug` attributes inside `template-parts/sections/topic-child-nav.php`.
3. **Section Partials:** Create `topic-featured-article.php`, `topic-latest-articles.php`, `topic-featured-research.php`, and `topic-community.php`. Build them to rely strictly on the incoming `$args['context']`.
4. **Endpoint Creation:** Implement the REST API route inside the newly created `inc/helper-functions/health-topic-rest.php` and load it via `functions.php`.
5. **Template Assembly:** Place the partial render calls into `front-page.php` and `taxonomy-health_topic.php`.
6. **Interaction Layer:** Build `assets/js/topic-navigation.js` to dispatch REST calls, handle pagination context injection, update UI active states, and perform the specific DOM node HTML swaps.

## User Review Required

> [!WARNING]
> Please review the updated plan above. If everything looks correct and accurately reflects the `front-page.php` adjustments, exact fallback strings, and pure vanilla JS approach, let me know so we can move forward with execution!
