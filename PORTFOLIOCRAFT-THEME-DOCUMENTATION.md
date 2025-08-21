# PortfolioCraft Theme - Complete Documentation

## 🚀 AI Agent Quick Start Notes

### Project Structure Overview
This is a **WordPress theme** located at: `themes/portfoliocraf/`
- **Type:** Modern WordPress theme with Elementor integration
- **Architecture:** Object-oriented PHP with singleton pattern
- **Main Entry:** `functions.php` → `inc/classes/class-main.php`
- **Global Access:** `portfoliocraft()` function provides access to all theme functionality
- **Dependencies:** Requires Rakmyat Core plugin for full functionality

### Key Project Information
- **Theme Slug:** `portfoliocraft` (note: directory is `portfoliocraf` - missing 't')
- **Main Class:** `portfoliocraft_Main` (singleton pattern)
- **Options System:** Redux Framework integration via theme-options files
- **Template System:** WordPress template hierarchy + custom template parts
- **Asset Loading:** Performance-optimized conditional loading

### Critical Files to Understand First
1. **`functions.php`** - Theme initialization and core setup
2. **`inc/classes/class-main.php`** - Core theme functionality (572 lines)
3. **`inc/theme-options/rakmyat-theme-options.php`** - Main options panel (1,893 lines)
4. **`style.css`** - Theme metadata (actual styles in assets/css/)

### Quick Architecture Map
```
Theme Root
├── functions.php (entry point)
├── Template files (index.php, single.php, page.php, etc.)
├── inc/
│   ├── classes/ (core functionality classes)
│   ├── theme-options/ (Redux framework integration)
│   ├── admin/ (admin interface customization)
│   └── demo-import/ (demo content system)
├── assets/ (CSS, JS, images, fonts, SCSS)
├── template-parts/ (reusable template components)
└── woocommerce/ (WooCommerce integration)
```

### Integration Points
- **Rakmyat Core Plugin:** Essential companion plugin (see other documentation file)
- **Elementor:** Page builder integration with custom widgets
- **WooCommerce:** E-commerce functionality
- **Redux Framework:** Theme options panel

### 📝 MAINTENANCE INSTRUCTIONS FOR AI AGENTS

#### CRITICAL: Keep This Documentation Updated
**When making ANY changes to this theme, you MUST update this documentation file:**

1. **File Changes:** If you modify, add, or remove files → Update file structure sections
2. **Function Changes:** If you modify class methods or functions → Update method descriptions
3. **Feature Changes:** If you add/remove features → Update feature lists and descriptions
4. **Integration Changes:** If you modify plugin integrations → Update integration sections
5. **Asset Changes:** If you modify CSS/JS structure → Update asset management sections

#### How to Update This Documentation
- **Location:** This file is at `themes/portfoliocraf/PORTFOLIOCRAFT-THEME-DOCUMENTATION.md`
- **Format:** Use clear markdown formatting with proper headings
- **Detail Level:** Provide enough detail for next AI agent to understand without reading code
- **Cross-References:** Update related sections when making changes
- **File Sizes:** Update line counts and file sizes when files change significantly

#### Documentation Sections That Need Regular Updates
- File structure and architecture
- Class methods and functionality
- Asset organization
- Integration points
- Performance features
- Template system changes

#### Before Making Major Changes
1. **Read this documentation completely** to understand current architecture
2. **Check integration with Rakmyat Core plugin** (see other documentation file)
3. **Test changes** with common theme functionality
4. **Update documentation** before completing your work
5. **Note any breaking changes** in the documentation

### 🎯 Common Development Tasks Quick Reference
- **Add new theme option:** Modify `inc/theme-options/rakmyat-theme-options.php`
- **Add new template:** Create in root or `template-parts/` directory
- **Modify sidebar:** Edit `inc/classes/class-main.php` sidebar methods
- **Add CSS/JS:** Place in `assets/` directory and enqueue in `functions.php`
- **Customize WooCommerce:** Modify files in `woocommerce/` directory

---

## 📋 Complete Editing Guide - What to Edit for Each Feature

### 🎨 Visual & Layout Changes

#### **If you need to edit single post page layout:**
- **Edit file:** `single.php` (main template structure)
- **Also edit:** `template-parts/content/content-single.php` (post content display)
- **Sidebar settings:** Modify `inc/classes/class-main.php` → `get_sidebar_value()` method
- **Styling:** Add CSS to `assets/css/` directory and enqueue in `functions.php`

#### **If you need to edit blog archive page:**
- **Edit file:** `index.php` (main blog listing template)
- **Post items:** `template-parts/content/archive/standard.php`
- **Page header:** Modify the header section in `index.php` lines 30-55
- **Pagination:** Handled by `portfoliocraft()->page->get_pagination()` in `inc/classes/class-page.php`

#### **If you need to edit single page layout:**
- **Edit file:** `page.php` (main page template)
- **Content display:** `template-parts/content/content-page.php`
- **Elementor detection:** Lines 24-33 in `page.php`
- **Page options:** `inc/theme-options/rakmyat-page-options.php`

#### **If you need to edit 404 error page:**
- **Edit file:** `404.php`
- **Custom 404 page:** Set via theme options, handled in lines 25-35
- **Default layout:** Lines 38-98 in `404.php`
- **Styling:** `assets/css/404.css` (enqueued automatically)

#### **If you need to edit search results page:**
- **Edit file:** `search.php`
- **Search header:** Lines 32-63 in `search.php`
- **Results display:** `template-parts/content/content-search.php`
- **No results:** `template-parts/content/content-none.php`
- **Styling:** `assets/css/consolidated-search.css`

#### **If you need to edit author archive page:**
- **Edit file:** `author.php`
- **Author info display:** Lines 65-71
- **Posts grid:** Lines 74-80
- **Styling:** Inline styles in lines 26-63 (can be moved to CSS file)

### 🔧 Header & Footer Modifications

#### **If you need to edit site header:**
- **Edit file:** `header.php` (basic HTML structure)
- **Header functionality:** `inc/classes/class-header.php`
- **Header display logic:** `portfoliocraft()->header->getHeader()` method
- **Header templates:** `template-parts/header/` directory
- **Header options:** In theme options panel

#### **If you need to edit site footer:**
- **Edit file:** `footer.php` (basic HTML structure)
- **Footer functionality:** `inc/classes/class-footer.php`
- **Footer display logic:** `portfoliocraft()->footer->getFooter()` method
- **Footer templates:** `template-parts/footer/` directory
- **Back-to-top button:** Lines 31-35 in `footer.php`

#### **If you need to edit navigation menus:**
- **Main menu creation:** `functions.php` lines 88-110
- **Menu locations:** Registered in theme options
- **Menu styling:** Add CSS to theme stylesheets
- **Menu functionality:** WordPress Customizer → Menus

### 💬 Comments & Forms

#### **If you need to edit comments system:**
- **Edit file:** `comments.php`
- **Comment list callback:** `portfoliocraft_comment_list` function (needs to be created)
- **Comment form args:** Lines 79-141 in `comments.php`
- **Comment styling:** `assets/css/comments.css`
- **Threading depth:** Line 55 `'max_depth' => 3`

#### **If you need to edit search form:**
- **Edit file:** `searchform.php`
- **Form functionality:** `assets/js/search-form.js`
- **Form styling:** `assets/css/search-form.css`
- **Clear button:** Lines 54-61 in `searchform.php`

### 🏪 WooCommerce Integration

#### **If you need to edit WooCommerce templates:**
- **Main integration:** `woocommerce.php`
- **WooCommerce functions:** `woocommerce/wc-function.php` (19KB, 401 lines)
- **Product widgets:** `woocommerce/content-widget-product.php`
- **WooCommerce scripts:** `woocommerce/js/` directory
- **Shop page settings:** Handled via `get_sidebar_value('shop')` method

### ⚙️ Theme Options & Customization

#### **If you need to add new theme options:**
- **Edit file:** `inc/theme-options/rakmyat-theme-options.php` (82KB, 1,893 lines)
- **Option functions:** `inc/theme-options/rakmyat-option-functions.php`
- **Theme actions:** `inc/theme-options/rakmyat-theme-actions.php`
- **Theme filters:** `inc/theme-options/rakmyat-theme-filters.php`

#### **If you need to add page-specific options:**
- **Edit file:** `inc/theme-options/rakmyat-page-options.php`
- **Meta box creation:** Uses Redux Framework meta boxes
- **Access options:** Use `portfoliocraft()->get_page_opt('option_name')`

#### **If you need to modify theme configuration:**
- **Edit file:** `inc/theme-options/rakmyat-theme-config.php`
- **Theme constants:** Define global settings here
- **Default values:** Set default option values

### 🎭 Template System

#### **If you need to add new template parts:**
- **Content templates:** `template-parts/content/` directory
- **Header templates:** `template-parts/header/` directory
- **Footer templates:** `template-parts/footer/` directory
- **Widget templates:** `template-parts/widgets/` directory
- **Category templates:** `template-parts/category/` directory

#### **If you need to create custom post type templates:**
- **Follow WordPress template hierarchy**
- **Single template:** `single-{post_type}.php`
- **Archive template:** `archive-{post_type}.php`
- **Template parts:** `template-parts/content/content-{post_type}.php`

### 📁 Asset Management

#### **If you need to add new CSS styles:**
- **Development:** Add to `assets/scss/` directory
- **Production:** Compile to `assets/css/` directory
- **Enqueue:** Add to `functions.php` in `portfoliocraft_enqueue_scripts()`
- **Conditional loading:** Use conditional checks like homepage detection

#### **If you need to add new JavaScript:**
- **Location:** `assets/js/` directory
- **Libraries:** `assets/js/libs/` for third-party libraries
- **Enqueue:** Add to `functions.php` in `portfoliocraft_enqueue_scripts()`
- **Dependencies:** Specify jQuery or other dependencies

#### **If you need to add new fonts:**
- **Font files:** `assets/fonts/` directory
- **Google Fonts:** Modify `portfoliocraft_preload_fonts()` in `functions.php`
- **CSS:** Add font-face declarations to stylesheets

### 🔌 Core Functionality

#### **If you need to modify theme initialization:**
- **Edit file:** `functions.php` (main entry point)
- **Core class:** `inc/classes/class-main.php`
- **Base functionality:** `inc/classes/class-base.php`
- **Theme instance:** Access via `portfoliocraft()` function

#### **If you need to modify sidebar functionality:**
- **Edit file:** `inc/classes/class-main.php`
- **Sidebar method:** `get_sidebar_value($page)` lines 482-525
- **Sidebar display:** `get_sidebar()` method lines 535-551
- **Sidebar template:** `sidebar.php`

#### **If you need to modify page functionality:**
- **Edit file:** `inc/classes/class-page.php` (20KB, 460 lines)
- **Site loader:** `get_site_loader()` method
- **Page titles:** `get_post_title()` and `get_page_title()` methods
- **Pagination:** `get_pagination()` method
- **Breadcrumbs:** `inc/classes/class-breadcrumb.php`

#### **If you need to modify blog functionality:**
- **Edit file:** `inc/classes/class-blog.php` (32KB, 502 lines)
- **Blog settings:** Various blog-related methods
- **Post formats:** Support for different post formats
- **Archive displays:** Blog archive functionality

### 🛠️ Admin Interface

#### **If you need to modify admin dashboard:**
- **Edit file:** `inc/admin/admin-dashboard.php`
- **Admin initialization:** `inc/admin/admin-init.php`
- **Admin pages:** `inc/admin/admin-page.php`
- **Plugin management:** `inc/admin/admin-plugins.php`

#### **If you need to add required plugins:**
- **Edit file:** `inc/admin/admin-require-plugins.php` (11KB, 272 lines)
- **Plugin recommendations:** `inc/admin/admin-plugins.php`

### 📦 Demo Import System

#### **If you need to modify demo import:**
- **Configuration:** `inc/demo-import/demo-config.php`
- **Import controller:** `inc/demo-import/theme-demo-controller.php` (33KB, 906 lines)
- **Import class:** `inc/demo-import/class-demo-import.php` (22KB, 684 lines)
- **Demo content:** `inc/demo-import/demo-content/` directory

### 🔍 Performance Optimization

#### **If you need to modify performance features:**
- **Script optimization:** `functions.php` → `portfoliocraft_dequeue_unused_assets()`
- **Font preloading:** `functions.php` → `portfoliocraft_preload_fonts()`
- **Conditional CSS:** `functions.php` → conditional enqueuing logic
- **Asset minification:** Handled in asset build process

### 🌐 Multilingual Support

#### **If you need to add translations:**
- **Text domain:** `portfoliocraft`
- **Language files:** `languages/` directory
- **Translation functions:** Use `esc_html__()`, `esc_attr__()`, etc.
- **WPML integration:** Theme options have WPML support built-in

### 🔐 Security & Validation

#### **If you need to modify security features:**
- **Input sanitization:** Used throughout theme files
- **Nonce verification:** Add to forms and AJAX calls
- **Capability checks:** Use `current_user_can()` for admin functions
- **Escape output:** Use `esc_html()`, `esc_attr()`, `esc_url()` functions

---

## Overview
PortfolioCraft is a modern, multipurpose WordPress theme designed for businesses, portfolios, and e-commerce websites. Built with Elementor page builder integration, WooCommerce compatibility, and a powerful theme options panel.

### Theme Information
- **Theme Name:** portfoliocraft
- **Version:** 1.0.0
- **Text Domain:** portfoliocraft
- **Author:** portfoliocraft-Themes
- **Requires PHP:** 7.4+
- **Requires WordPress:** 5.8+
- **Tested up to:** 6.4

### Key Features
- Fully responsive and mobile-friendly
- Elementor page builder integration
- WooCommerce ready for e-commerce
- RTL language support
- Dark mode support built-in
- Customizable through theme options
- Performance and SEO optimized
- Custom post types support
- Extensive customization options

## File Structure & Architecture

### Root Files

#### `style.css`
- **Purpose:** Main theme stylesheet containing theme metadata
- **Content:** Theme header information required by WordPress
- **Note:** Actual styles are organized in the `assets/css/` directory

#### `functions.php`
- **Purpose:** Core theme initialization and functionality
- **Key Functions:**
  - Theme text domain loading for translations
  - Script and style enqueueing with performance optimization
  - Google Fonts preloading
  - Default menu creation
  - Demo import system integration
- **Performance Features:**
  - Conditional CSS loading (homepage-specific styles)
  - Unused asset removal
  - Font preloading optimization

#### Template Files

##### `index.php` - Blog Archive Template
- **Purpose:** Main blog listing page
- **Features:**
  - Configurable sidebar support
  - Custom page header with subtitle and title
  - Post loop with pagination
  - Content template parts integration

##### `header.php` - Site Header
- **Purpose:** Site header and HTML document start
- **Features:**
  - Responsive viewport meta tags
  - WordPress head hook integration
  - Smooth scroll wrapper (conditional)
  - Site loader integration
  - Skip-to-content accessibility link

##### `footer.php` - Site Footer
- **Purpose:** Site footer and HTML document end
- **Features:**
  - Back-to-top button (configurable)
  - Smooth scroll wrapper closing
  - WordPress footer hook
  - Custom anchor target hooks

##### `single.php` - Single Post Template
- **Purpose:** Individual blog post display
- **Features:**
  - Sidebar configuration
  - Post format support
  - Comments integration
  - Template part system

##### `page.php` - Page Template
- **Purpose:** Individual page display
- **Features:**
  - Elementor compatibility detection
  - Dynamic container classes
  - Sidebar support
  - Comments integration

##### `404.php` - Error Page Template
- **Purpose:** 404 error page display
- **Features:**
  - Custom Elementor page support
  - Default fallback design
  - Floating decorative elements
  - Search functionality
  - Navigation options

##### `search.php` - Search Results Template
- **Purpose:** Search results display
- **Features:**
  - Search query display
  - Results count
  - Search suggestions
  - Popular categories
  - Consolidated CSS loading

##### `author.php` - Author Archive Template
- **Purpose:** Author posts archive
- **Features:**
  - Author information display
  - Theme color integration
  - Responsive grid layout
  - Author avatar and description

#### Other Templates

##### `comments.php` - Comments System
- **Purpose:** Comments display and form
- **Features:**
  - Threaded comments (max depth: 3)
  - Custom comment form styling
  - SVG icons integration
  - Accessibility features
  - Password protection support

##### `searchform.php` - Search Form
- **Purpose:** Consistent search form across the site
- **Features:**
  - Unique ID generation for accessibility
  - JavaScript localization
  - Clear search functionality
  - ARIA labels and accessibility

##### `sidebar.php` - Dynamic Sidebar
- **Purpose:** Simple sidebar loader
- **Functionality:** Uses theme's dynamic sidebar system

##### `woocommerce.php` - WooCommerce Integration
- **Purpose:** WooCommerce template override
- **Features:** Custom WooCommerce layout integration

## Core Architecture

### Class System (`inc/classes/`)

#### `class-main.php` - Main Theme Class
- **Purpose:** Core theme functionality manager
- **Pattern:** Singleton pattern for consistent access
- **Key Methods:**
  - `get_theme_opt()` - Theme options retrieval
  - `get_page_opt()` - Page-specific options
  - `get_opt()` - Combined options with inheritance
  - `get_sidebar_value()` - Sidebar configuration
  - `require_folder()` - Dynamic file loading

#### `class-base.php` - Base Class
- **Purpose:** Foundation class for WordPress hook management
- **Methods:**
  - `add_action()` - WordPress action wrapper
  - `add_filter()` - WordPress filter wrapper
- **Helper Functions:**
  - `pxl_action()` - Custom theme action trigger

#### `class-page.php` - Page Handler
- **Purpose:** Page-related functionality
- **Features:**
  - Site loader/preloader management
  - Page and post title handling
  - Breadcrumb navigation
  - Pagination system
  - Link pages for multi-page posts

#### `class-header.php` - Header Management
- **Purpose:** Header functionality (27 lines)
- **Integration:** Works with theme header system

#### `class-footer.php` - Footer Management
- **Purpose:** Footer functionality (35 lines)
- **Integration:** Works with theme footer system

#### `class-blog.php` - Blog Functionality
- **Purpose:** Blog-specific features (502 lines)
- **Features:** Advanced blog management

#### `class-breadcrumb.php` - Breadcrumb System
- **Purpose:** Navigation breadcrumb functionality (649 lines)
- **Features:** Comprehensive breadcrumb generation

### Theme Options System (`inc/theme-options/`)

#### `rakmyat-theme-options.php` - Main Options Panel
- **Size:** 82KB, 1,893 lines
- **Purpose:** Complete theme customization panel
- **Framework:** Redux Framework integration

#### `rakmyat-theme-actions.php` - Theme Actions
- **Size:** 22KB, 596 lines
- **Purpose:** WordPress action hooks and callbacks

#### `rakmyat-theme-config.php` - Configuration
- **Size:** 8.6KB, 190 lines
- **Purpose:** Theme configuration and constants

#### `rakmyat-theme-filters.php` - Filter System
- **Size:** 35KB, 982 lines
- **Purpose:** WordPress filter hooks and modifications

#### `rakmyat-option-functions.php` - Option Functions
- **Size:** 20KB, 604 lines
- **Purpose:** Helper functions for theme options

#### `rakmyat-page-options.php` - Page Options
- **Size:** 16KB, 332 lines
- **Purpose:** Page-specific customization options

#### `rakmyat-theme-check.php` - Theme Validation
- **Size:** 8.7KB, 264 lines
- **Purpose:** Theme compatibility and validation

#### `rakmyat-theme-functions.php` - Theme Functions
- **Size:** 33KB, 889 lines
- **Purpose:** Core theme utility functions

### Admin System (`inc/admin/`)

#### `admin-init.php` - Admin Initialization
- **Size:** 6.3KB, 187 lines
- **Purpose:** Admin area setup and configuration

#### `admin-dashboard.php` - Dashboard Customization
- **Size:** 660B, 32 lines
- **Purpose:** WordPress dashboard modifications

#### `admin-page.php` - Admin Pages
- **Size:** 1.2KB, 67 lines
- **Purpose:** Custom admin page creation

#### `admin-plugins.php` - Plugin Management
- **Size:** 2.8KB, 110 lines
- **Purpose:** Plugin recommendation and management

#### `admin-require-plugins.php` - Required Plugins
- **Size:** 11KB, 272 lines
- **Purpose:** Plugin dependency management

#### `admin-templates.php` - Admin Templates
- **Size:** 341B, 15 lines
- **Purpose:** Admin template loading

### Demo Import System (`inc/demo-import/`)

#### `demo-config.php` - Demo Configuration
- **Size:** 3.5KB, 102 lines
- **Purpose:** Demo import configuration

#### `theme-demo-controller.php` - Demo Controller
- **Size:** 33KB, 906 lines
- **Purpose:** Advanced demo import management

#### `class-demo-import.php` - Demo Import Class
- **Size:** 22KB, 684 lines
- **Purpose:** Demo import functionality

### Assets Structure (`assets/`)

#### CSS Directory (`assets/css/`)
- Theme stylesheets organized by functionality
- Performance-optimized loading
- Conditional CSS for different page types

#### JavaScript Directory (`assets/js/`)
- Theme scripts and functionality
- Library integrations
- Performance optimizations

#### Images Directory (`assets/img/`)
- Theme images and graphics
- Optimized assets

#### Fonts Directory (`assets/fonts/`)
- Custom font files
- Typography assets

#### SCSS Directory (`assets/scss/`)
- Source SCSS files for development
- Organized by components

### Template Parts (`template-parts/`)

#### Content Templates (`template-parts/content/`)
- Post content templates
- Page content templates
- Archive templates
- Search result templates

#### Header Templates (`template-parts/header/`)
- Various header layouts
- Header components

#### Footer Templates (`template-parts/footer/`)
- Footer layout options
- Footer components

#### Widget Templates (`template-parts/widgets/`)
- Widget templates
- Sidebar components

#### Category Templates (`template-parts/category/`)
- Category archive templates
- Taxonomy templates

### WooCommerce Integration (`woocommerce/`)

#### `wc-function.php` - WooCommerce Functions
- **Size:** 19KB, 401 lines
- **Purpose:** WooCommerce customization and integration

#### `content-widget-product.php` - Product Widget
- **Size:** 655B, 29 lines
- **Purpose:** Product widget template

#### JavaScript Integration (`woocommerce/js/`)
- WooCommerce-specific scripts
- Enhanced functionality

## Global Function Access

### `portfoliocraft()` Function
- **Purpose:** Global access to theme singleton instance
- **Usage:** `portfoliocraft()->get_theme_opt('option_name')`
- **Available Methods:**
  - Theme option retrieval
  - Page option management
  - Sidebar configuration
  - Component access (header, footer, page, blog)

## Key Features & Integrations

### Elementor Integration
- Full Elementor page builder support
- Custom Elementor widgets (via Rakmyat Core)
- Template detection and conditional loading

### WooCommerce Support
- Complete e-commerce integration
- Custom product templates
- Shopping cart functionality
- Payment gateway support

### Performance Optimization
- Conditional asset loading
- Font preloading
- Unused CSS removal
- Optimized script loading

### Multilingual Support
- WPML integration
- RTL language support
- Translation-ready code

### Customization System
- Extensive theme options
- Page-specific options
- Color management
- Typography controls
- Layout options

### SEO Optimization
- Semantic HTML structure
- Proper heading hierarchy
- Meta tag optimization
- Schema markup ready

## Development Notes

### Coding Standards
- WordPress coding standards compliant
- PSR-4 autoloading where applicable
- Proper sanitization and escaping
- Security best practices

### Browser Compatibility
- Modern browser support
- Progressive enhancement
- Responsive design principles

### Accessibility
- WCAG 2.1 compliance
- Proper ARIA labels
- Keyboard navigation support
- Screen reader compatibility

### Performance
- Optimized database queries
- Efficient asset loading
- Caching-friendly code
- Mobile-first approach

## Integration with Rakmyat Core

The theme is designed to work seamlessly with the Rakmyat Core plugin, which provides:
- Advanced Elementor widgets
- Custom post types
- Demo import functionality
- Extended theme options
- Template system
- Performance enhancements

## Maintenance & Updates

### Theme Updates
- Version control through style.css header
- Backward compatibility maintained
- Database schema updates handled
- Settings migration support

### Child Theme Support
- Full child theme compatibility
- Function override capability
- Style customization support
- Safe update process

This documentation provides a complete overview of the PortfolioCraft theme architecture, functionality, and integration points. It serves as a comprehensive reference for developers working with or extending the theme. 