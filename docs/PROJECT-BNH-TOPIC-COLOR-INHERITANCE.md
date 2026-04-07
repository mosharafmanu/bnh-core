# Topic Color Inheritance

## Purpose

Define one editorial color per parent `health_topic` term and let child topics inherit that color automatically.

## Editor Rule

- Set `Topic Color` only on parent `health_topic` terms
- Child terms do not get their own color field
- Child terms inherit the selected parent color automatically

## Field

- Field name: `topic_color`
- Field type: `Text`
- Expected format: hex color, for example `#0B3276`

## ACF Instructions

`Enter a hex color for this parent health topic. Child topics automatically inherit the parent topic color. Current brand colors for reference: Prostate #0B3276, Diabetes #330C65, Hormone #560606, General #804103.`

## Theme Logic

- The field lives in `acf-json/group_health_topic_color.json`
- Admin visibility is handled in `inc/helper-functions/health-topic-featured-fields.php`
- Inheritance helpers live in `inc/helper-functions/health-topic-colors.php`

## Helper Behavior

- Parent term:
  - read `topic_color`
- Child term:
  - resolve parent term
  - read the parent term `topic_color`
- If a valid hex color is set, use it directly
- If no ACF value is set, no topic color is applied

## Context Values

`bnh_get_health_topic_context()` now exposes:

- `active_topic_color_key`
- `active_topic_color_value`

These are available for theme templates and section styling.
