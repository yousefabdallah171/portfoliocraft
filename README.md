# PortfolioCraft Theme

**Version:** 1.0.0
**Purpose:** Modern portfolio and creative WordPress theme

## JavaScript Libraries

### Animation Libraries
- **bundled-lenis** (latest) - Smooth scrolling library
- **wow** v1.0.0 - Scroll-triggered animations (WOW.js)

### Utility Libraries
- **cookie** v1.4.1 - Cookie management
- **cursor** v1.0.0 - Custom cursor effects

### GSAP Animation Suite
- **gsap** v3.12.5 - Core GSAP animation library
- **ScrollTrigger** v3.12.5 - Scroll-based animations
- **ScrollSmoother** v3.12.5 - Smooth scroll effect (conditional)
- **DrawSVGPlugin** v3.12.5 - SVG drawing animations
- **SplitText** v3.12.5 - Text animation effects

## File Structure

```
portfoliocraft/
├── functions.php              # Main theme file (clean entry point)
├── inc/
│   ├── classes/               # Theme classes
│   │   ├── class-main.php     # Main theme class
│   │   ├── class-header.php   # Header functionality
│   │   ├── class-footer.php   # Footer functionality
│   │   └── ...
│   ├── theme-options/         # Theme options & configuration
│   │   ├── rakmyat-theme-actions.php    # All add_action hooks
│   │   ├── rakmyat-theme-filters.php    # All add_filter hooks
│   │   ├── rakmyat-theme-functions.php  # Helper functions
│   │   └── rakmyat-theme-options.php    # Redux options
│   ├── admin/                 # Admin dashboard
│   └── demo-import/           # Demo import system
├── template-parts/            # Template components
├── woocommerce/               # WooCommerce integration
└── assets/
    ├── js/
    │   ├── libs/              # JavaScript libraries (4 files)
    │   ├── gsap/              # GSAP animation suite (5 files)
    │   ├── theme.js           # Main theme script
    │   └── menu.js            # Menu functionality
    └── css/                   # Stylesheets
```

## Theme Features

- Redux Framework integration
- Elementor compatibility
- WooCommerce support
- Advanced theme options
- Demo import system
- Custom widgets
- Mega menu support
- GSAP animations
- Smooth scrolling

## Production Ready

✅ All testing files removed
✅ All documentation files cleaned
✅ Unused libraries removed
✅ Clean, organized code structure
✅ Performance optimized
✅ GSAP Pro animations included
