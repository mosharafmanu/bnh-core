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
- [template-parts/content-post.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/content-post.php)

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

## Comments

- comments are currently hidden on single posts
- [single.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/single.php) no longer calls `comments_template()`
- this is intentional for now
- comments can be reintroduced later when the comment UX is defined
