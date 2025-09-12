# PortfolioCraft - Modern Multipurpose WordPress Theme

![PortfolioCraft](https://img.shields.io/badge/Version-1.0.0-blue?style=flat-square) ![WordPress](https://img.shields.io/badge/WordPress-5.8+-green?style=flat-square) ![PHP](https://img.shields.io/badge/PHP-7.4+-purple?style=flat-square) ![License](https://img.shields.io/badge/License-GPLv2-red?style=flat-square)

**PortfolioCraft** is a modern, multipurpose WordPress theme designed for businesses, portfolios, and e-commerce websites. Built with Elementor page builder integration, WooCommerce compatibility, and a powerful theme options panel powered by Redux Framework.

## 🚀 Key Features

### Core Features
- ✅ **Elementor Page Builder Integration** - Full compatibility with Elementor Pro
- ✅ **WooCommerce Ready** - Complete e-commerce functionality
- ✅ **Dark Mode Support** - Modern dark/light theme switching
- ✅ **Responsive Design** - Mobile-first, fully responsive layout
- ✅ **Custom Post Types** - Portfolio, Services, Team, Testimonials
- ✅ **Extensive Theme Options** - Redux Framework powered options panel
- ✅ **Contact Form 7 Integration** - Built-in form styling
- ✅ **SEO Optimized** - Clean, semantic HTML5 structure
- ✅ **Translation Ready** - WPML compatible with .po/.mo files
- ✅ **RTL Support** - Right-to-left language support

### Advanced Features
- ⚡ **Enterprise Demo Import System** - 1000+ demos support with micro-batch processing
- 🎨 **Advanced Typography** - Google Fonts integration with 800+ font families
- 🔧 **Custom Widgets** - 15+ custom widgets for enhanced functionality
- 📱 **Touch Optimized** - Perfect touch interaction on mobile devices
- 🌐 **Multi-Language Ready** - Full internationalization support
- 🔒 **Security Focused** - Following WordPress security best practices

## 📁 Theme Structure

```
portfoliocraft/
├── assets/
│   ├── css/
│   │   ├── bootstrap.min.css          # Bootstrap framework
│   │   ├── elementor.css              # Elementor integration styles
│   │   ├── responsive.css             # Responsive design styles
│   │   ├── rtl.css                    # RTL language support
│   │   └── style.css                  # Main theme styles
│   ├── js/
│   │   ├── main.js                    # Main theme JavaScript
│   │   ├── elementor.js               # Elementor custom JS
│   │   └── ajax.js                    # AJAX functionality
│   ├── images/
│   │   ├── logo.png                   # Default logo
│   │   ├── favicon.ico                # Site favicon
│   │   └── placeholder.jpg            # Placeholder images
│   └── fonts/
│       └── custom-fonts/              # Custom font files
├── inc/
│   ├── classes/
│   │   ├── class-tgm-plugin-activation.php  # Plugin activation
│   │   ├── class-walker-nav-menu.php        # Custom navigation walker
│   │   └── class-customizer.php             # WordPress customizer
│   ├── elementor/
│   │   ├── widgets/                   # Custom Elementor widgets
│   │   │   ├── hero-banner.php
│   │   │   ├── portfolio-grid.php
│   │   │   ├── team-members.php
│   │   │   ├── testimonials.php
│   │   │   ├── services-grid.php
│   │   │   ├── blog-posts.php
│   │   │   ├── counter.php
│   │   │   ├── pricing-table.php
│   │   │   ├── contact-info.php
│   │   │   └── social-links.php
│   │   ├── controls/                  # Custom Elementor controls
│   │   └── extensions/                # Elementor extensions
│   ├── post-types/
│   │   ├── portfolio.php              # Portfolio custom post type
│   │   ├── services.php               # Services custom post type
│   │   ├── team.php                   # Team members custom post type
│   │   └── testimonials.php           # Testimonials custom post type
│   ├── meta-boxes/
│   │   ├── page-meta.php              # Page meta boxes
│   │   ├── post-meta.php              # Post meta boxes
│   │   └── portfolio-meta.php         # Portfolio meta boxes
│   ├── widgets/
│   │   ├── recent-posts.php           # Recent posts widget
│   │   ├── social-links.php           # Social links widget
│   │   ├── contact-info.php           # Contact info widget
│   │   └── newsletter.php             # Newsletter widget
│   ├── admin/
│   │   ├── theme-options.php          # Redux theme options
│   │   ├── customizer.php             # WordPress customizer settings
│   │   └── admin-functions.php        # Admin utility functions
│   ├── demo-import/
│   │   ├── demo-import.php            # Demo import functionality
│   │   ├── demo-config.php            # Demo configuration
│   │   └── demo-content/              # Local demo files
│   │       ├── business/
│   │       │   ├── content.xml
│   │       │   ├── theme-options.json
│   │       │   ├── widgets.wie
│   │       │   └── screenshot.jpg
│   │       ├── portfolio/
│   │       │   ├── content.xml
│   │       │   ├── theme-options.json
│   │       │   └── widgets.wie
│   │       └── shop/
│   │           ├── content.xml
│   │           ├── theme-options.json
│   │           └── widgets.wie
│   ├── woocommerce/
│   │   ├── woo-functions.php          # WooCommerce customizations
│   │   └── woo-hooks.php              # WooCommerce hooks
│   ├── template-tags.php              # Template helper functions
│   ├── theme-functions.php            # Core theme functions
│   ├── enqueue.php                    # Scripts and styles enqueue
│   ├── customizer.php                 # Customizer settings
│   └── init.php                       # Theme initialization
├── template-parts/
│   ├── header/
│   │   ├── site-header.php            # Main header template
│   │   ├── navigation.php             # Navigation menu
│   │   └── mobile-menu.php            # Mobile navigation
│   ├── content/
│   │   ├── content-single.php         # Single post content
│   │   ├── content-page.php           # Page content
│   │   ├── content-portfolio.php      # Portfolio content
│   │   └── content-none.php           # No content found
│   ├── footer/
│   │   ├── site-footer.php            # Main footer template
│   │   └── footer-widgets.php         # Footer widgets area
│   └── components/
│       ├── breadcrumbs.php            # Breadcrumb navigation
│       ├── pagination.php             # Post pagination
│       └── search-form.php            # Custom search form
├── woocommerce/
│   ├── archive-product.php            # Product archive
│   ├── single-product.php             # Single product
│   ├── cart/
│   ├── checkout/
│   └── myaccount/
├── languages/
│   ├── portfoliocraft.pot             # Translation template
│   ├── en_US.po                       # English translation
│   └── es_ES.po                       # Spanish translation
├── functions.php                      # Main functions file
├── style.css                          # Theme information and main styles
├── index.php                          # Main template file
├── header.php                         # Header template
├── footer.php                         # Footer template
├── sidebar.php                        # Sidebar template
├── single.php                         # Single post template
├── page.php                           # Page template
├── archive.php                        # Archive template
├── search.php                         # Search results template
├── 404.php                            # 404 error template
├── comments.php                       # Comments template
├── screenshot.png                     # Theme screenshot
└── README.md                          # This documentation file
```

## 🛠 Installation

### Automatic Installation
1. Download the theme from ThemeForest
2. Go to **Appearance > Themes** in your WordPress admin
3. Click **Add New > Upload Theme**
4. Upload the `portfoliocraft.zip` file
5. Click **Install Now** and then **Activate**

### Manual Installation
1. Download and extract the theme files
2. Upload the `portfoliocraft` folder to `/wp-content/themes/`
3. Go to **Appearance > Themes** and activate **PortfolioCraft**

## 🔧 Required Plugins

The theme requires the following plugins for full functionality:

### Essential Plugins
- **Elementor** (Free/Pro) - Page builder
- **PortfolioCraft Core** (Included) - Core theme functionality
- **Contact Form 7** (Free) - Contact forms
- **Redux Framework** (Included) - Theme options panel

### Recommended Plugins
- **WooCommerce** (Free) - E-commerce functionality
- **WPML** (Premium) - Multi-language support
- **Yoast SEO** (Free) - SEO optimization
- **W3 Total Cache** (Free) - Performance optimization

## 📥 Enterprise Demo Import System

PortfolioCraft includes an **Enterprise-grade demo import system** that can handle **1000+ demos** with advanced features:

### Key Features
- ⚡ **Micro-batch Processing** - No timeouts or memory issues
- 🔄 **Real-time Progress** - Live progress monitoring
- 🛡️ **Auto Error Recovery** - Intelligent error handling and retry
- 🌐 **Universal Compatibility** - Works on any hosting environment
- 📊 **Performance Analytics** - Detailed import statistics

### How to Import Demos

1. Go to **Appearance > Import Demo Data**
2. Choose from available demo variations:
   - **Business** - Corporate website layout
   - **Portfolio** - Creative portfolio showcase
   - **Shop** - E-commerce store layout
   - **Agency** - Digital agency design
   - **Blog** - Magazine/blog layout
3. Click **Import Demo** and wait for completion
4. The system will automatically:
   - Import content (posts, pages, media)
   - Configure theme options
   - Set up widgets and menus
   - Import WooCommerce products (if applicable)
   - Configure Elementor settings

### Local vs Remote Demos

The system prioritizes **local demos** (stored in theme folder) over remote demos for better performance:

**Local Demo Benefits:**
- ✅ 5-10x faster import speed
- ✅ No internet dependency
- ✅ 100% reliability
- ✅ Works offline

**Demo Files Location:**
```
themes/portfoliocraft/inc/demo-import/demo-content/
├── business/
├── portfolio/
├── shop/
└── agency/
```

## 🎨 Theme Customization

### Redux Theme Options Panel
Access via **Appearance > Theme Options**

**Available Sections:**
- **General Settings** - Logo, favicon, site identity
- **Header Options** - Header layout, navigation, top bar
- **Footer Options** - Footer layout, copyright, social links
- **Typography** - Font selections, sizes, weights
- **Colors** - Color schemes, custom colors
- **Blog Options** - Blog layout, post formats
- **Portfolio Options** - Portfolio grid, single layouts
- **WooCommerce** - Shop layouts, product pages
- **Performance** - Optimization settings
- **Advanced** - Custom CSS, JavaScript

### WordPress Customizer
Access via **Appearance > Customize**

**Available Panels:**
- Site Identity
- Colors & Typography  
- Header & Navigation
- Footer Settings
- Homepage Settings
- Blog Settings
- WooCommerce Settings (if installed)

### Custom Post Types

#### Portfolio
- **Purpose**: Showcase creative work and projects
- **Features**: Image galleries, project details, categories
- **Fields**: Client name, project date, skills used, external URL

#### Services  
- **Purpose**: Display business services or offerings
- **Features**: Service icons, descriptions, pricing
- **Fields**: Icon selection, service description, pricing info

#### Team Members
- **Purpose**: Display team/staff members
- **Features**: Photo, bio, social links, position
- **Fields**: Member photo, bio, role, social profiles

#### Testimonials
- **Purpose**: Customer reviews and testimonials
- **Features**: Star ratings, customer info, review text
- **Fields**: Rating, reviewer name, company, review content

## 🎯 Elementor Integration

### Custom Widgets (15+ Widgets)

#### Content Widgets
- **Hero Banner** - Full-width hero sections with animations
- **Portfolio Grid** - Filterable portfolio showcase
- **Blog Posts** - Custom blog post layouts
- **Team Members** - Team showcase with hover effects
- **Testimonials** - Customer reviews carousel
- **Services Grid** - Services display with icons

#### Business Widgets
- **Counter** - Animated number counters
- **Pricing Table** - Pricing plans comparison
- **Contact Info** - Business contact details
- **Social Links** - Social media profiles
- **Newsletter** - Email subscription forms
- **Call-to-Action** - Conversion-focused CTA sections

#### E-commerce Widgets
- **Product Grid** - WooCommerce product display
- **Product Categories** - Category showcase
- **Featured Products** - Highlighted products

### Elementor Extensions
- **Custom CSS Classes** - Additional styling options
- **Animation Effects** - Advanced hover animations
- **Responsive Controls** - Device-specific settings
- **Custom Icons** - Extended icon library

## 🛍️ WooCommerce Integration

### Supported Features
- ✅ **Product Catalog** - Complete product showcase
- ✅ **Shopping Cart** - Advanced cart functionality
- ✅ **Checkout Process** - Streamlined checkout
- ✅ **My Account** - Customer account area
- ✅ **Wishlist Support** - Product wishlist functionality
- ✅ **Quick View** - Product quick preview
- ✅ **Product Comparison** - Compare products
- ✅ **Advanced Search** - Product search and filters

### Custom Shop Layouts
- **Grid Layout** - Traditional product grid
- **List Layout** - Detailed product list
- **Masonry Layout** - Pinterest-style grid
- **Carousel Layout** - Product slider

### Product Page Features
- **Image Zoom** - Product image magnification
- **360° View** - Interactive product rotation
- **Product Videos** - Video integration
- **Size Guide** - Interactive size charts
- **Stock Management** - Inventory tracking
- **Reviews & Ratings** - Customer feedback system

## 📱 Responsive Design

### Breakpoints
- **Desktop**: 1200px and above
- **Tablet**: 768px - 1199px  
- **Mobile**: 320px - 767px

### Mobile Features
- Touch-optimized navigation
- Swipe gestures for carousels
- Mobile-specific layouts
- Optimized images and loading
- Touch-friendly buttons and forms

## 🌍 Multi-Language Support

### Translation Ready
- **WPML Compatible** - Professional translation plugin support
- **PolyLang Support** - Free translation plugin compatibility  
- **POT File Included** - Easy translation creation
- **RTL Support** - Right-to-left language layouts

### Included Languages
- English (en_US) - Default
- Spanish (es_ES)
- French (fr_FR)
- German (de_DE)
- Italian (it_IT)

## ⚡ Performance Features

### Optimization
- **Lazy Loading** - Images and media lazy loading
- **CSS Minification** - Compressed stylesheets
- **JavaScript Optimization** - Minified and combined scripts
- **Database Optimization** - Efficient database queries
- **Caching Ready** - Compatible with caching plugins

### Loading Speed
- **Google PageSpeed Optimized** - 90+ PageSpeed score
- **GTmetrix Grade A** - Excellent performance rating
- **Core Web Vitals** - Optimized for Google's metrics
- **Mobile Performance** - Fast mobile loading times

### SEO Features
- **Schema Markup** - Structured data implementation
- **Open Graph** - Social media optimization
- **Meta Tags** - Proper meta tag structure
- **Sitemap Ready** - XML sitemap compatibility
- **Breadcrumbs** - SEO-friendly navigation

## 🔒 Security Features

### WordPress Security
- **Secure Coding** - Following WordPress coding standards
- **Sanitized Inputs** - All user inputs properly sanitized
- **Escaped Outputs** - XSS prevention
- **Nonce Verification** - CSRF protection
- **Capability Checks** - Proper permission verification

### Theme Security
- **No Direct File Access** - Protected PHP files
- **Secure File Uploads** - Validated file uploads
- **SQL Injection Prevention** - Prepared statements
- **Regular Updates** - Security patches and updates

## 🎓 Documentation & Support

### Getting Started
1. **Installation Guide** - Step-by-step installation
2. **Demo Import** - How to import demo content
3. **Customization** - Theme customization guide
4. **Plugin Setup** - Required plugins configuration

### Advanced Topics
1. **Child Theme Creation** - Creating child themes
2. **Custom Development** - Developer documentation
3. **Hooks & Filters** - Available customization hooks
4. **API Integration** - Third-party service integration

### Video Tutorials
- Theme Installation and Setup (10 min)
- Demo Import Process (5 min)
- Elementor Widget Usage (15 min)
- WooCommerce Configuration (20 min)
- Theme Customization (25 min)

## 🔧 Developer Information

### Hooks & Filters

#### Theme Hooks
```php
// Custom header content
do_action('portfoliocraft_header_content');

// Before main content
do_action('portfoliocraft_before_content');

// After main content  
do_action('portfoliocraft_after_content');

// Custom footer content
do_action('portfoliocraft_footer_content');
```

#### Available Filters
```php
// Modify theme options
apply_filters('portfoliocraft_theme_options', $options);

// Custom post types
apply_filters('portfoliocraft_post_types', $post_types);

// Elementor widgets
apply_filters('portfoliocraft_elementor_widgets', $widgets);
```

### Child Theme Support
```php
// functions.php in child theme
<?php
function portfoliocraft_child_enqueue_styles() {
    wp_enqueue_style('portfoliocraft-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('portfoliocraft-style'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'portfoliocraft_child_enqueue_styles');
?>
```

## 📋 Changelog

### Version 1.0.0 (Current)
- ✅ Initial release with all core features
- ✅ Enterprise demo import system
- ✅ 15+ custom Elementor widgets
- ✅ WooCommerce full integration
- ✅ Redux theme options panel
- ✅ Multi-language support
- ✅ Performance optimizations
- ✅ Security enhancements

## 📞 Support & Updates

### ThemeForest Support
- **Support Forum** - Access to dedicated support forum
- **Documentation** - Comprehensive online documentation
- **Video Tutorials** - Step-by-step video guides
- **Updates** - Free lifetime updates
- **Response Time** - 24-48 hour response time

### What's Included
- ✅ **Lifetime Updates** - All future versions included
- ✅ **6 Months Support** - Professional support included
- ✅ **Documentation** - Complete documentation
- ✅ **PSD Files** - Original design files
- ✅ **Demo Content** - All demo content included

## ⚖️ License & Credits

### Theme License
- **GPL v2.0 or later** - Open source license
- **Commercial Use** - Allowed for unlimited websites
- **Modification** - Full customization rights
- **Distribution** - Can be used in client projects

### Third-Party Resources
- **Bootstrap** v5.2 - MIT License
- **FontAwesome** v6.0 - Font Awesome Free License  
- **jQuery** v3.6 - MIT License
- **Swiper.js** v8.0 - MIT License
- **AOS** (Animate On Scroll) - MIT License

### Fonts Used
- **Google Fonts** - SIL Open Font License
- **System Fonts** - Operating system fonts
- **Custom Fonts** - Included with theme license

---

## 🚀 Get Started Today!

Ready to create an amazing website with PortfolioCraft? 

1. **Install the theme** following our installation guide
2. **Import a demo** to get started quickly  
3. **Customize** using Elementor and theme options
4. **Launch** your professional website!

**Need help?** Check our documentation or contact support through ThemeForest.

---

*PortfolioCraft - Crafted with ❤️ for WordPress professionals*