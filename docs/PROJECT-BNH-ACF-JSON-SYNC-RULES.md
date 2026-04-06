# ACF JSON Sync Rules

## Layout Folder Rule

- Every flexible content layout must live in:
  - `template-parts/sections/{layout-name}/`
- The folder name must match the layout name exactly
- The PHP file should also match the layout name:
  - `template-parts/sections/{layout-name}/{layout-name}.php`

## JSON Sync Requirement

- Any flexible content layout change must also be reflected in:
  - `acf-json/group_flexible_content.json`
- Theme code and ACF JSON must stay in sync

## Timestamp Rule

- When `group_flexible_content.json` is changed, the `modified` timestamp must be updated
- This helps ACF detect the latest local JSON version correctly
