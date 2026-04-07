# Video Field Structure & Behavior System - Project Guidelines

This document outlines the standard architecture, behavior, and implementation requirements for the reusable video field system within the BNH theme. 

## 1. Architecture & Content Management

- Use ACF (Advanced Custom Fields) Flexible Content for all page builder functionality.
- Write professional, production-ready code following WordPress coding standards.
- Implement conditional logic throughout to avoid unnecessary code execution.
- Avoid code duplication to improve maintainability and SEO.

## 2. Performance & Optimization (Primary Focus)

- Prioritize lightweight, fast-loading code - performance is critical.
- Minimize CSS and JavaScript file sizes.
- Load assets conditionally only when needed.
- Use lazy loading for images and videos where appropriate.
- Optimize database queries and avoid unnecessary loops.

## 3. SEO Requirements (Top Priority)

- SEO-first approach in all development decisions.
- Semantic HTML5 markup with proper heading hierarchy.
- Clean, non-duplicated content structure.
- Proper meta tags and schema markup.
- Fast page load times (Core Web Vitals optimization).
- Mobile-first responsive design.

## 4. Image Optimization Strategy (Critical)

- Implement a responsive pictures function for image rendering across all video posters and fallbacks.

## 5. Code Quality Standards

- Follow WordPress coding standards (WPCS).
- Use proper escaping and sanitization for security (`esc_url`, `esc_attr`, etc.).
- Write modular, reusable functions.
- Add clear comments for complex logic.
- Ensure cross-browser compatibility.

## 6. Video Field System Specifications

### 6.1. Video Sources Support
- **Self-Hosted** (WordPress Media Library) - MP4/WebM files with poster image
- **YouTube** - Embed via URL
- **Vimeo** - MP4 direct URL with poster image
- **CDN/External URL** - External video URL with poster image

### 6.2. Video Behaviors
- **Autoplay** - Auto-play with custom controls
- **Hover** - Play on hover, pause on leave
- **Click to Open Popup** - Click to open video in modal

### 6.3. ACF Field Structure
Create a group field with these sub-fields:

1. **Video Source** (select field)
    - Field name: `video_source`
    - Choices: `self_host`, `youtube`, `vimeo`, `cdn`
    - Default: `self_host`
2. **Video Behavior** (select field)
    - Field name: `video_behavior`
    - Choices: `autoplay`, `hover`, `onclick-popup`
    - Default: `autoplay`
3. **Conditional Fields** based on video source:
    - **Self-Hosted**: `video_self_host_file` (file), `video_self_host_poster` (image)
    - **YouTube**: `video_youtube_url` (URL)
    - **Vimeo**: `video_vimeo_url` (URL), `video_vimeo_poster` (image)
    - **CDN**: `video_cdn_url` (URL), `video_cdn_poster` (image)

### 6.4. Implementation Requirements
- Create a helper function `bnh_render_video()` that:
    - Accepts a video data array and optional arguments
    - Supports all three behaviors (autoplay, hover, onclick-popup)
    - Handles all four video sources
    - Includes parameters for: autoplay, muted, loop, controls, width, height, custom classes
    - Adds appropriate data attributes for JavaScript behavior handling
    - Follows WordPress coding standards
- Template usage pattern:

```php
if ( function_exists( 'bnh_render_video' ) ) {
    $video_behavior = ! empty( $video_data['video_behavior'] ) ? $video_data['video_behavior'] : 'autoplay';
    
    bnh_render_video(
        $video_data,
        [
            'behavior'        => $video_behavior,
            'autoplay'        => true,
            'muted'           => true,
            'loop'            => true,
            'controls'        => false,
            'class'           => 'section-video',
            'container_class' => 'video-container',
        ]
    );
}
```

### 6.5. Additional Features
- Low power mode overlay for autoplay behavior
- Custom video controls for autoplay (not YouTube)
- Play overlay button for onclick-popup behavior
- Autoplay on scroll option
- Poster image fallback support
- Mobile-friendly (`playsinline` attribute)
- Browser autoplay policy compliance (muted for autoplay)

### 6.6. File Organization
- Helper function: `inc/helper-functions/video-renderer.php`
- ACF JSON: Export to `acf-json/` folder
- Include proper documentation in function comments

*This structure should be reusable across all sections of the website where video is needed (hero sections, content sections, product galleries, etc.).*
