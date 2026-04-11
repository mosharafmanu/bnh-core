# Image Size Decision Note

## Current State

The theme image layer is already BNH-named and currently uses the shared generated size ladder from:

- `inc/image-sizes.php`
- `inc/helper-functions/responsive-picture.php`
- `IMAGE_SIZE_POLICY.md`

Current generated sizes:

- `bhn-100`
- `bhn-300`
- `bhn-405`
- `bhn-688`
- `bhn-828`
- `bhn-972`
- `bhn-1200`

## Final Figma Image Usage Map

These are the confirmed BNH design targets that should drive the next image-size review.

### Topic Hub

- Featured card media: `688 × 452`
- Latest articles 4-column media: `405 × 248`

### Homepage

- `Book Your 1-on-1` media/content 50/50 section: `827.59 × 824`
- `Leading Doctor` 4-column media: `405 × 480`

### Author Page

- Author profile 50/50 media: `261 × 337`

### Article Page

- Article full-width media type 1: `972 × 168`
- Article full-width media type 2: `972 × 912`

### Article Sidebar

- Similar articles media: `405 × 144`

## Current Mapping Direction

The current BNH implementation now uses these repeated widths as the shared project ladder:

- `bhn-405` for 4-column card and sidebar-card media
- `bhn-688` for topic featured card media
- `bhn-828` for 50/50 media-content sections
- `bhn-972` for article full-width media

This keeps the image system aligned with the actual BNH layouts instead of previous-project assumptions.

## Practical Note

After changing the registered size ladder, regenerate thumbnails so existing uploads receive the new `bhn-*` derivatives.
