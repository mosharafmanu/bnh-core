# Leading Doctors Data Source

The `leading_doctors` flexible section supports two data modes.

## Data Source

- `Manual Input`
  - Uses the existing repeater:
    - image
    - name
    - role

- `Dynamic Authors`
  - Uses selected WordPress users from the author list
  - Selection is restricted to the `author` role only
  - Card values come from:
    - image: user avatar
    - name: `display_name`
    - role: ACF user field `job_title`

## Editor UI

- `Data Source` uses an ACF `button_group`

## Files

- Layout:
  - [template-parts/sections/leading_doctors.php](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/template-parts/sections/leading_doctors.php)
- ACF JSON:
  - [acf-json/group_flexible_content.json](/Applications/AMPPS/www/ClientProjects/WordPress/2026/bensnaturalhealth/wp-content/themes/bnh-core/acf-json/group_flexible_content.json)

## Note

- Manual repeater remains intact.
- Dynamic mode currently uses the user avatar, not a separate custom profile image field.
