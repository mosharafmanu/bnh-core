# BNH Core Theme - Development TODO

## Current Phase

- [ ] Theme development phase has started on top of the completed architecture layer
- [ ] Build presentation and template structure inside `bnh-core` only
- [ ] Keep theme work aligned with the finalized routing and SEO rules

## Completed (Do Not Touch)

- [x] `health_topic` taxonomy architecture is finalized
- [x] URL structure and routing system are finalized
- [x] Collision-safe slug handling is in place
- [x] Strict parent validation is in place
- [x] SEO-safe redirect system is in place
- [x] Canonical and sitemap validation work is completed
- [x] Legacy taxonomy UI cleanup is in place
- [x] Project documentation has been copied into `/docs`
- [x] Clean bootstrap in `functions.php` is in place

## In Progress

- [ ] Theme-level navigation and archive template implementation
- [ ] Reusable section planning for archive and single content rendering
- [ ] Template structure review to keep `bnh-core` clean and scalable

## Next Steps (Execution Order)

- [ ] Header Navigation (Parent Topics)
  - [ ] Build navigation for the 4 parent `health_topic` terms
  - [ ] Show correct active state based on current topic context
  - [ ] Keep navigation data-driven from taxonomy structure

- [ ] Child Topic Navigation
  - [ ] Build child-topic navigation under the active parent topic
  - [ ] Show correct active child state on child archives
  - [ ] Support topic-aware state on single posts

- [ ] `taxonomy-health_topic.php`
  - [ ] Handle parent archive output
  - [ ] Handle child archive output
  - [ ] Keep parent vs child behavior explicit and maintainable

- [ ] Archive Content (Reusable Sections)
  - [ ] Create reusable archive sections under `template-parts/sections/`
  - [ ] Separate layout, query context, and rendering concerns
  - [ ] Keep archive output modular and reusable

- [ ] Single Post Template
  - [ ] Build single post template with topic-aware logic
  - [ ] Keep active parent and child navigation in sync with the post topic context
  - [ ] Ensure single post output respects the finalized permalink structure

## Rules (Do Not Break)

- [ ] DO NOT hardcode `/blog`
- [ ] ALWAYS use `home_url()`
- [ ] Routing is CHILD-FIRST
- [ ] Wrong parent URL must return `404`
- [ ] Canonical must match final URL
- [ ] Do NOT modify routing/redirect logic without approval
- [ ] Do NOT copy from old theme unless explicitly needed

## Cleanup Phase (Later)

- [ ] Identify unused files in `bnh-core`
- [ ] Remove leftover test or unused files
- [ ] Ensure clean structure across `inc/`, `template-parts/`, `sections/`, and `docs/`
- [ ] Remove SEO verification helper after QA

## Testing (After Build)

- [ ] Parent archive URLs
- [ ] Child archive URLs
- [ ] Single post URLs
- [ ] Redirect behavior (`301`)
- [ ] No redirect loops
- [ ] Canonical correctness
- [ ] Sitemap URLs

## Goal

- [ ] Build a clean, scalable, taxonomy-driven WordPress theme on top of the completed routing and SEO-safe architecture
