# Single Article Behaviors

Single posts now use a post-specific template flow in `bnh-core`.

## Template Flow

- Outer single-page shell:
  - [single.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/single.php)
- Post-specific article layout:
  - [template-parts/content-post.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/content-post.php)
- Generic fallback for other post types:
  - [template-parts/content.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/content.php)

## Writer / Reviewer Popups

### Markup
- [template-parts/content-post.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/content-post.php)

### Data Sources
- Writer:
  - WordPress post author
  - `get_the_author()`
  - `get_the_author_meta( 'description' )`
  - `get_author_posts_url()`
  - `get_avatar()`
- Reviewer:
  - ACF post field `medically_reviewed_by`
  - field type: `user`
  - return format: `array`
- User fields used for both writer and reviewer popup content:
  - ACF user field `job_title`
  - ACF user field `popup_info`
  - fallback to WordPress user `description`

### Link Sources
- `See Full Bio`
  - author archive URL
- `Our Editorial Process`
  - [inc/helper-functions/post-utilities.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/inc/helper-functions/post-utilities.php)
  - function: `bnh_core_get_editorial_guidelines_url()`
  - uses local page slug `editorial-guidelines` if present
  - otherwise falls back to `https://www.bensnaturalhealth.com/editorial-guidelines`

### Interaction
- [assets/js/scripts.js](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/assets/js/scripts.js)
- selector:
  - `.entry-meta__person-trigger`
- behavior:
  - toggles `.is-open` on the person meta item
  - updates `aria-expanded`
  - closes other open person popups
  - closes on outside click

### Styles
- [assets/css/bnh-core-theme.css](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/assets/css/bnh-core-theme.css)
- main selectors:
  - `.entry-meta__person-popup`
  - `.entry-meta__item--person.is-open`
  - `.entry-meta__person-popup-links`

### Meta Row Labels
- `Writer:`
- `Reviewer:`
- `Read in:`
- `Updated:`

## Article Summary

### Markup
- [template-parts/content-post.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/content-post.php)

### Data Sources
- primary:
  - ACF post field `article_summary`
- fallback:
  - excerpt
  - trimmed post content

### Helpers
- [inc/helper-functions/post-utilities.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/inc/helper-functions/post-utilities.php)
- functions:
  - `bnh_core_get_post_summary_text()`
  - `bnh_core_get_post_summary_markup()`

### Interaction
- [assets/js/scripts.js](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/assets/js/scripts.js)
- selector:
  - `.single-article__summary-toggle`
- behavior:
  - toggles `.is-expanded`
  - updates `aria-expanded`

### Notes
- if summary markup contains list items, the block becomes collapsible
- if not, it renders as a normal summary block without the expand button

## TOC / Article Contents

### Markup
- [template-parts/content-post.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/content-post.php)

### Helper
- [inc/helper-functions/post-utilities.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/inc/helper-functions/post-utilities.php)
- function:
  - `bnh_core_get_post_table_of_contents()`

### Behavior
- parses post body HTML
- finds `h2` headings
- injects `id` attributes into those headings
- builds the TOC item list from those `h2` titles
- returns:
  - updated content
  - TOC item array

### Notes
- TOC is automatic
- it is not manually entered in ACF

## Sources

### Markup
- [template-parts/sections/single-post-trust.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/sections/single-post-trust.php)

### Data Source
- ACF post field:
  - `sources`

### Interaction
- [assets/js/scripts.js](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/assets/js/scripts.js)
- selector:
  - `.single-article__sources-toggle`
- behavior:
  - toggles `.is-expanded`
  - updates `aria-expanded`

### TOC Integration
- if `sources` has content, `Source` is appended to the TOC automatically

## Update History

### Markup
- [template-parts/sections/single-post-trust.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/sections/single-post-trust.php)

### Data Source
- dynamic WordPress post data
- helper:
  - `bnh_core_get_post_update_history()`
  - [inc/helper-functions/post-utilities.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/inc/helper-functions/post-utilities.php)

### Behavior
- `Created on`
  - from post publish date
- `Created by`
  - from `post_author`
- `Updated on`
  - from post modified date
- `Updated by`
  - from the latest non-autosave revision author when available
  - falls back to post author if no usable revision exists
- user job title is pulled from ACF user field:
  - `job_title`

### Interaction
- [assets/js/scripts.js](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/assets/js/scripts.js)
- selector:
  - `.single-article__update-history-toggle`
- behavior:
  - toggles `.is-expanded`
  - updates `aria-expanded`

## Single Article Trust Section

- Markup:
  - [template-parts/sections/single-post-trust.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/sections/single-post-trust.php)
- Purpose:
  - renders the post-bottom trust content area before `Explore More`
- Site Settings fields used:
  - `single_article_review_heading`
  - `single_article_review_content`
  - `single_article_editorial_heading`
  - `single_article_editorial_content`
  - `single_article_disclaimer_heading`
  - `single_article_disclaimer_content`

## Explore More

### Markup
- [template-parts/content-post.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/content-post.php)

### Data Source
- ACF post field:
  - `related_post`

### Behavior
- if `related_post` contains content, it is rendered below the trust section under the `Explore More` heading

## Previous / Next Navigation

- default WordPress post navigation is no longer rendered on single posts
- removed from:
  - [single.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/single.php)

## Inline Topic Community

- Shortcode:
  - `[bnh_topic_community]`
- Renderer:
  - `bnh_core_topic_community_shortcode()`
  - [inc/helper-functions/post-utilities.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/inc/helper-functions/post-utilities.php)
- Rendered section template:
  - [template-parts/sections/topic-community.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/sections/topic-community.php)
- Purpose:
  - lets editors place the reusable topic community section inside article body content

## Inline Book Consultation

- Shortcode:
  - `[bnh_book_consultation]`
- Renderer:
  - `bnh_core_book_consultation_shortcode()`
  - [inc/helper-functions/post-utilities.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/inc/helper-functions/post-utilities.php)
- Rendered section template:
  - [template-parts/sections/book_consultation.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/sections/book_consultation.php)
- Content source:
  - Site Settings ACF
  - same source is used on the homepage flexible section and in single-post insertions
  - not post-specific fields
- Site Settings fields used:
  - `book_consultation_heading`
  - `book_consultation_intro_text`
  - `book_consultation_items`
  - `book_consultation_button`
  - `book_consultation_image`
- Homepage flexible layout note:
  - the `Book Consultation` flexible section is now only a placement hook
  - its content is managed in `Site Settings → Book Consultation Section`

## Shortcode Picker

- TinyMCE / Classic editor toolbar button:
  - `BNH Shortcodes`
- Registration:
  - [functions.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/functions.php)
- Editor button script:
  - [assets/js/admin-shortcode-picker.js](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/assets/js/admin-shortcode-picker.js)
- Shortcode registry source:
  - `bnh_core_get_editor_shortcodes()`
  - [inc/helper-functions/post-utilities.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/inc/helper-functions/post-utilities.php)
- Current insert option:
  - `Topic Community` → `[bnh_topic_community]`
  - `Book Consultation` → `[bnh_book_consultation]`
- Future shortcode additions should be added to the PHP registry so the editor menu stays in sync

## Gutenberg Block

- Block name:
  - `bnh-core/topic-community`
- Inserter label:
  - `Topic Community`
- Registration:
  - [functions.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/functions.php)
- Editor script:
  - [assets/js/editor-topic-community-block.js](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/assets/js/editor-topic-community-block.js)
- Render callback:
  - `bnh_core_render_topic_community_block()`
  - [inc/helper-functions/post-utilities.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/inc/helper-functions/post-utilities.php)
- Runtime output:
  - same reusable section renderer as the shortcode
  - no duplicate markup path

## Gutenberg Block: Book Consultation

- Block name:
  - `bnh-core/book-consultation`
- Inserter label:
  - `Book Consultation`
- Registration:
  - [functions.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/functions.php)
- Editor script:
  - [assets/js/editor-topic-community-block.js](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/assets/js/editor-topic-community-block.js)
- Render callback:
  - `bnh_core_render_book_consultation_block()`
  - [inc/helper-functions/post-utilities.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/inc/helper-functions/post-utilities.php)
- Runtime output:
  - same reusable section renderer as the shortcode
  - no duplicate markup path

## Comments

- comments are currently hidden on single posts
- [single.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/single.php) no longer calls `comments_template()`
- this is intentional for now
- comments can be reintroduced later when the comment UX is defined
