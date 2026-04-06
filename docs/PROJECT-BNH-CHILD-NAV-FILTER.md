# Child Topic Navigation Filtering

## Purpose

- Hide empty child `health_topic` terms from the child navigation
- Improve UX by avoiding navigation links that lead to empty content states

## Behavior

- Only child terms with at least one published `post` are shown
- The default active child is the first child term that has posts
- If no child term under a parent has posts, the child navigation stays hidden

## Implementation

Updated files:

- `inc/helper-functions/health-topic-context.php`
- `template-parts/sections/topic-child-nav.php`

Where filtering happens:

- Filtering happens in the theme context/helper layer
- The nav template simply renders the filtered `child_terms` it receives

How post existence is checked:

- A narrow theme-side query checks whether a child term has at least one published `post`
- Visible child terms are built from that filtered result set

## Notes

- This logic lives in the theme, not the plugin
- It does not change routing, URLs, redirects, or canonical behavior
- It works with the existing AJAX / REST topic-content behavior

## Future Consideration

- If needed later, empty child terms could be shown again behind a theme-level flag
