# Image Size Decision Note

## Current State

The theme image layer has been cleaned to use BNH naming:

- `inc/image-sizes.php`
- `inc/helper-functions/responsive-picture.php`

The shared generated size names now use the `bhn-*` prefix.

## Important Decision Rule

Do not finalize the long-term image-size ladder yet based only on inherited structure from the previous project.

Before making further changes to the image-size system, collect the real BNH image usage requirements for:

- section / component name
- rendered width on desktop
- rendered width on tablet
- rendered width on mobile
- whether the image needs fixed crop or flexible height
- whether the same image pattern is reused elsewhere

## Why

The final decisions for:

- generated image sizes
- token-to-size mapping
- `size_group` usage
- responsive `sizes` behavior

should be based on real BNH layouts, not previous-project assumptions.

## Next Review Inputs Needed

Before the next image-system refinement, provide:

1. image use cases by section/component
2. approximate display widths by breakpoint
3. crop vs flexible-height requirements
4. which patterns are reused multiple times

## Guidance

Once the real BNH image usage map is available, refine:

- `inc/image-sizes.php`
- `inc/helper-functions/responsive-picture.php`

using the shared-size policy and the actual BNH design requirements.
