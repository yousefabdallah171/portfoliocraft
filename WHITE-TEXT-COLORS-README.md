# White Text Colors Documentation

This document explains how to use the new white text color controls in the portfoliocraft theme.

## Overview

The theme now includes dedicated color controls for white and light text colors in the **Global Colors** section of the theme options. These colors are automatically generated as CSS custom properties (variables) and can be used throughout the theme for consistent styling.

## Theme Options Location

Navigate to: **WordPress Admin → Theme Options → Global Colors → White & Light Text Colors**

## Available Color Controls

### 1. White Text Color
- **Option ID**: `white_text_color`
- **Default**: `#ffffff`
- **CSS Variable**: `--white-text-color`
- **Usage**: Primary white text for dark backgrounds

### 2. Light Text Color  
- **Option ID**: `light_text_color`
- **Default**: `#f5f5f5`
- **CSS Variable**: `--light-text-color`
- **Usage**: Light gray text for secondary content on dark backgrounds

### 3. Off-White Color
- **Option ID**: `off_white_color` 
- **Default**: `#fafafa`
- **CSS Variable**: `--off-white-color`
- **Usage**: Slightly off-white for softer contrast on dark backgrounds

### 4. Light Gray Text
- **Option ID**: `light_gray_text_color`
- **Default**: `#e0e0e0`
- **CSS Variable**: `--light-gray-text-color`
- **Usage**: Muted light text for meta information on dark backgrounds

## CSS Usage Examples

### Basic Usage
```css
/* Instead of: color: #fff; */
.dark-section {
    color: var(--white-text-color, #ffffff);
}

/* Instead of: color: white; */
.header-dark .menu-item {
    color: var(--white-text-color, #ffffff);
}
```

### Different Text Hierarchy
```css
/* Primary white text */
.dark-overlay .title {
    color: var(--white-text-color, #ffffff);
}

/* Secondary light text */
.dark-overlay .subtitle {
    color: var(--light-text-color, #f5f5f5);
}

/* Off-white for description */
.dark-overlay .description {
    color: var(--off-white-color, #fafafa);
}

/* Muted text for meta information */
.dark-overlay .meta {
    color: var(--light-gray-text-color, #e0e0e0);
}
```

### Button and Interactive Elements
```css
/* Primary button with white text */
.btn-primary {
    background: var(--primary-color);
    color: var(--white-text-color, #ffffff);
}

/* Button hover state */
.btn-primary:hover {
    color: var(--white-text-color, #ffffff);
}
```

### Utility Classes
```css
.text-white {
    color: var(--white-text-color, #ffffff) !important;
}

.text-light {
    color: var(--light-text-color, #f5f5f5) !important;
}

.text-off-white {
    color: var(--off-white-color, #fafafa) !important;
}

.text-light-gray {
    color: var(--light-gray-text-color, #e0e0e0) !important;
}
```

## Migration Guide

### From Hardcoded Colors

**Before:**
```css
.dark-header {
    color: #ffffff;
}

.overlay-content {
    color: white;
}

.dark-button:hover {
    color: #fff;
}
```

**After:**
```css
.dark-header {
    color: var(--white-text-color, #ffffff);
}

.overlay-content {
    color: var(--white-text-color, #ffffff);
}

.dark-button:hover {
    color: var(--white-text-color, #ffffff);
}
```

## Files Updated

The following files have been updated to support white text color variables:

1. **Theme Options**: `inc/theme-options/rakmyat-theme-options.php`
   - Added white text color controls to Global Colors section

2. **CSS Generation**: `inc/theme-options/rakmyat-theme-config.php`  
   - Updated `portfoliocraft_configs()` to include white text colors
   - CSS variables are automatically generated and injected

3. **Example Implementation**: `assets/css/home.css`
   - Updated existing white color usage to use new variables
   - Demonstrates proper implementation

4. **Variable Usage Guide**: `assets/css/white-text-variables.css`
   - Comprehensive examples of how to use the variables
   - Covers all common use cases

## Best Practices

### 1. Always Use Variables
Instead of hardcoding white colors, always use the CSS variables:
```css
/* Good */
color: var(--white-text-color, #ffffff);

/* Bad */  
color: #ffffff;
color: white;
color: #fff;
```

### 2. Provide Fallbacks
Always include fallback values for browser compatibility:
```css
color: var(--white-text-color, #ffffff);
```

### 3. Choose Appropriate Variables
Use the right variable for the context:
- `--white-text-color`: Primary white text
- `--light-text-color`: Secondary/subtitle text  
- `--off-white-color`: Softer white contrast
- `--light-gray-text-color`: Muted/meta text

### 4. Dark Background Context
These variables are specifically designed for use on dark backgrounds:
```css
.dark-section {
    background: #1a1a1a;
    color: var(--white-text-color, #ffffff);
}

.dark-section .subtitle {
    color: var(--light-text-color, #f5f5f5);
}
```

## Browser Support

CSS custom properties (variables) are supported in all modern browsers:
- Chrome 49+
- Firefox 31+  
- Safari 9.1+
- Edge 16+

For older browsers, the fallback values will be used.

## Testing

To test the white text color controls:

1. Go to **WordPress Admin → Theme Options → Global Colors**
2. Scroll to **White & Light Text Colors** section
3. Change the colors and save
4. View pages with dark backgrounds to see changes
5. Check elements like buttons, overlays, and dark sections

## Troubleshooting

### Colors Not Changing
- Ensure you've saved the theme options
- Clear any caching plugins
- Check if CSS is being overridden by more specific selectors

### Variables Not Available
- Make sure `portfoliocraft_inline_styles()` is being called
- Check that the function is enqueued properly with `wp_add_inline_style()`
- Verify the CSS is being output in the page head

### Browser Issues
- Check browser support for CSS custom properties
- Ensure fallback values are provided
- Test in multiple browsers

For additional support, check the theme documentation or contact support.