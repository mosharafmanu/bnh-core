# Single Article Behaviors

This theme now supports the key live-site article behaviors in `bnh-core`.

## Included

- Author popup on the `Written by` meta item
- Article Summary block
- Article Contents block generated from post `h2` headings
- Sources section with expand/collapse behavior

## Data Sources

- Author popup:
  - user `job_title`
  - user `popup_info`
  - fallback to user `description`
- Article Summary:
  - ACF field `article_summary`
  - fallback to excerpt/content summary
- Sources:
  - ACF field `sources`

## Implementation

- Markup:
  - [template-parts/content.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/content.php)
- Helpers:
  - [inc/helper-functions/post-utilities.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/inc/helper-functions/post-utilities.php)
- Interactions:
  - [assets/js/scripts.js](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/assets/js/scripts.js)
- Styles:
  - [assets/css/bnh-core-theme.css](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/assets/css/bnh-core-theme.css)

## Notes

- TOC is generated from post body `h2` headings.
- If `sources` exists, a `Source` item is appended to the TOC.
- The author popup stays theme-side and uses existing user fields.
