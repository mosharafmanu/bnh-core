# Image Size Policy

## Purpose

This project uses a shared image-size system to keep uploads lighter, reduce server bloat, and still support responsive images through `picture` and `srcset`.

The goal is to avoid generating many near-duplicate image files for each layout or component.

## Core Principle

Use a small approved set of reusable image sizes across the whole site.

Do not create section-specific image sizes unless there is a clear visual or functional reason that cannot be solved by reusing an existing size.

## Approved Shared Sizes

These are the approved generated sizes for this project:

- `100`
- `150x150` WordPress thumbnail
- `300`
- `600`
- `750`
- `900`
- `1200`

Notes:

- Width-based sizes should use flexible height based on the uploaded image aspect ratio unless there is a strong reason to crop.
- Avoid adding sizes that are visually too close to an existing approved width.
- Do not create near-duplicate widths such as `578`, `590`, `596`, and `600` for different sections.

## Default Rules

1. Reuse the approved shared size ladder whenever possible.
2. Prefer width-based flexible-height derivatives by default.
3. Only create a unique cropped size if the design truly depends on a fixed aspect ratio.
4. Use `picture` and `srcset` with the approved shared sizes so the browser can choose the most appropriate image.
5. Keep the existing section tokens or helper APIs stable where possible, and map them internally to the shared size ladder.

## When A New Image Size Is Allowed

A new generated image size should only be added if all of the following are true:

1. No approved shared size can be reused.
2. The layout has a real visual requirement that cannot be handled with CSS or `object-fit`.
3. The new size will be reused in more than one place, or it solves a meaningful design problem.
4. The reason is documented before implementation.

## Required Documentation For Any New Size

If a new size is proposed, document:

- where it will be used
- expected visual width on desktop
- expected visual width on mobile
- whether it is cropped or flexible-height
- why an existing approved size is not sufficient

## Decision Process For Developers And AI

When implementing a new section:

1. Estimate the real rendered width of the image.
2. Reuse the nearest approved shared size.
3. Adjust the `sizes` attribute before introducing a new generated file.
4. Use CSS cropping or `object-fit` if the layout needs framing but a new generated size is not justified.
5. Only propose a new image size after checking all existing approved options.

## What To Avoid

- Do not register image sizes per component.
- Do not generate manual `1x`, `1.5x`, and `2x` variants for every layout.
- Do not create slightly different sizes for multiple sections that can share one output.
- Do not add image sizes just because a design file shows a slightly different rendered width.

## Implementation Guidance

- Keep image helper APIs stable for templates.
- Map section-level image tokens to the shared size ladder internally.
- Prefer changing `sizes` / `srcset` behavior before creating additional generated sizes.
- Regenerate thumbnails after changing the approved size list.

## Short Instruction For Future AI Sessions

Use the shared image-size ladder for this project. Do not add new image sizes unless there is a documented visual requirement that cannot be solved by reusing an approved size. Avoid near-duplicate widths and keep `picture` / `srcset` based on the approved shared set.
