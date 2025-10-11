
# PortfolioCraft Theme - Developer Documentation

**Version:** 1.0.1
**Last Updated:** 2025-10-11

## 1. Introduction

Welcome to the developer documentation for the PortfolioCraft WordPress theme. This document is designed to help you understand the theme's structure, features, and customization options. Whether you are setting up the theme for the first time, customizing it for a client, or a developer looking to extend its functionality, this guide is for you.

### 1.1. Theme Philosophy

PortfolioCraft is a **presentation-layer theme**. Its primary responsibility is to control the **look and feel** of your website. It is designed to be lightweight, flexible, and highly customizable through the WordPress Customizer and Elementor.

Crucially, PortfolioCraft works as part of a pair with the **`rakmyat-core` plugin**. This is a fundamental concept to understand:

*   **PortfolioCraft Theme (This Theme):** Handles all design, styling, and presentation. This includes colors, typography, layout, and the templates for pages, posts, etc.
*   **Rakmyat Core Plugin:** Handles all core functionality. This includes the Elementor widgets, the Theme Builder, the Mega Menu system, and custom post types.

This separation of concerns is a modern best practice that offers several advantages:

*   **No Theme Lock-In:** You can switch to another Rakmyat theme in the future without losing your portfolio items, custom widgets, or other core features.
*   **Maintainability:** It keeps the theme's code focused on presentation, making it easier to manage and update.
*   **Performance:** The theme remains lean, as heavy functional logic is offloaded to the plugin.

### 1.2. Who is this documentation for?

*   **End Users:** Who want to understand how to use the theme's features.
*   **Theme Customizers:** Who want to make CSS changes or modify template files.
*   **Developers:** Who need to integrate custom functionality or create a child theme.

### 1.3. Essential Prerequisites

Before you begin, ensure you have:

1.  **Installed and activated the PortfolioCraft theme.**
2.  **Installed and activated the required `rakmyat-core` plugin.**
3.  **Installed and activated the Elementor plugin.**
4.  (Optional but Recommended) Created a **child theme** for your customizations.

---

## 2. Architecture and Core Concepts

PortfolioCraft is built on a flexible and extensible architecture.

### 2.1. The Main Class: `inc/classes/class-main.php`

Similar to the core plugin, the theme uses a **singleton pattern** for its main class, `portfoliocraft_Main`. This class is the central hub for the theme's functionality.

You can access the single instance of this class anywhere using the `portfoliocraft()` global function.

**Key Responsibilities:**

*   **Initialization:** It loads all other necessary classes and files from the `inc/` directory.
*   **Component Management:** It instantiates and manages the core components of the theme, such as the header, footer, page, and blog handlers (`portfoliocraft_Header`, `portfoliocraft_Footer`, etc.).
*   **Option Retrieval:** It provides a set of powerful functions (`get_opt`, `get_theme_opt`, `get_page_opt`) for accessing theme options and page-specific meta settings.

**Example: Accessing the main theme class**

```php
// Get the singleton instance
$theme_instance = portfoliocraft();

// Use a method from the instance, e.g., to get the sidebar configuration
$sidebar_config = $theme_instance->get_sidebar_value('blog');
```

### 2.2. Directory Structure

*   `/assets`: Contains the theme's CSS, JavaScript, images, and fonts.
*   `/inc`: Home to the theme's core PHP logic.
    *   `/inc/classes`: The main `portfoliocraft_Main` class and other component classes.
    *   `/inc/demo-import`: Configuration for the One-Click Demo Import.
    *   `/inc/theme-options`: Configuration for the Redux Framework theme options panel.
*   `/template-parts`: Contains reusable template files (e.g., for the header, footer, content loops) that are included in the main template files. **This is where you will make most of your template modifications.**
*   `/woocommerce`: Contains template overrides for the WooCommerce plugin.
*   `functions.php`: The main entry point for the theme. It loads the core classes and sets up basic theme support.
*   `style.css`: Contains the theme's header information (name, version, author) and is required by WordPress. **The actual theme styles are in the `/assets/css` directory.**
*   **Main Template Files:** `index.php`, `page.php`, `single.php`, `archive.php`, etc. These files control the overall structure of different page types.

### 2.3. Parent and Child Themes

PortfolioCraft is designed to be a **parent theme**. This means that for any customizations beyond the theme options, **you should create a child theme.**

**Why use a child theme?**

*   **Safe Updates:** When you update the PortfolioCraft parent theme to a new version, your customizations will not be overwritten.
*   **Organization:** It keeps your custom code separate from the parent theme's code.

**How to create a child theme:**

1.  Create a new folder in your `wp-content/themes/` directory (e.g., `portfoliocraft-child`).
2.  Inside this folder, create a `style.css` file with the following header:

    ```css
    /*
     Theme Name:   PortfolioCraft Child
     Theme URI:    http://example.com/portfoliocraft-child/
     Description:  PortfolioCraft Child Theme
     Author:       Your Name
     Author URI:   http://example.com
     Template:     portfoliocraft
     Version:      1.0.0
    */
    ```
    The `Template: portfoliocraft` line is the most important part. It tells WordPress that this is a child theme of PortfolioCraft.

3.  Create a `functions.php` file in your child theme's folder to enqueue the parent theme's stylesheet:

    ```php
    <?php
    add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
    function my_theme_enqueue_styles() {
        wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    }
    ?>
    ```

Now you can activate your child theme and safely add your own CSS, functions, and template overrides.

---

## 3. Theme Options and Customization

PortfolioCraft offers a powerful and flexible customization system.

### 3.1. The Redux Theme Options Panel

All global theme options are managed through a theme options panel powered by the **Redux Framework**. This panel is configured in the `inc/theme-options/` directory.

*   **`rakmyat-theme-options.php`:** This file defines the structure of the theme options panel, including all the sections, subsections, and fields.
*   **`rakmyat-theme-config.php`:** This file is crucial. It takes the values from the theme options (e.g., the primary color) and makes them available to the theme. It is also responsible for generating the CSS variables that control the theme's styling.

**How to add a new theme option:**

1.  Open `inc/theme-options/rakmyat-theme-options.php`.
2.  Find the section where you want to add your new option.
3.  Add a new field to the `$fields` array, following the [Redux documentation](https://devs.redux.io/core-fields/text.html).
4.  To use your new option, you can retrieve its value using the `portfoliocraft()->get_theme_opt('your_option_id')` function.

### 3.2. The CSS Variable System

PortfolioCraft uses a modern CSS variable system for styling. This makes it incredibly easy to maintain a consistent design and to change the look of the entire site from the theme options.

**How it works:**

1.  The theme options panel saves a color value (e.g., `primary_color` = `#FF3D00`).
2.  The `portfoliocraft_inline_styles()` function in `inc/theme-options/rakmyat-theme-config.php` reads this value.
3.  It then generates a CSS variable and injects it into the `<head>` of the site:

    ```css
    :root {
        --primary-color: #FF3D00;
    }
    ```
4.  The theme's stylesheets then use this variable:

    ```css
    .some-element {
        background-color: var(--primary-color);
    }
    ```

**To customize the CSS:**

*   **The best way:** Use the theme options panel to change the colors, fonts, etc.
*   **For more advanced changes:** Add your own custom CSS to your child theme's `style.css` file. You can override the existing CSS variables or write new rules.

**Example: Changing the primary color in a child theme**

```css
/* In your child theme's style.css */
:root {
    --primary-color: #00A86B; /* Change to a new color */
}
```

### 3.3. Retrieving Option Values in Code

The `portfoliocraft_Main` class provides a set of helper functions for getting option values. It's important to use the correct one.

*   `portfoliocraft()->get_theme_opt('setting_id', 'default_value')`
    *   **Use this for:** Getting a **global** theme option from the Redux panel.
    *   **Example:** `portfoliocraft()->get_theme_opt('primary_color', '#000000')`

*   `portfoliocraft()->get_page_opt('setting_id', 'default_value')`
    *   **Use this for:** Getting a **page-specific** setting from a meta box.
    *   **Example:** `portfoliocraft()->get_page_opt('page_specific_layout', 'default_layout')`

*   `portfoliocraft()->get_opt('setting_id', 'default_value')`
    *   **This is the most powerful and commonly used function.**
    *   **How it works:** It first tries to get the page-specific option (`get_page_opt`). If that option doesn't exist or is set to a special "inherit" value, it falls back to the global theme option (`get_theme_opt`).
    *   **Use this for:** Any setting that can be set globally but also overridden on a per-page basis (e.g., sidebar layout, page title visibility).
    *   **Example:** `portfoliocraft()->get_opt('sidebar_layout', 'right-sidebar')`

---

## 4. Template Files and Customization

### 4.1. Overriding Template Files

To modify a template file, **you should never edit the parent theme's files directly.** Instead, you should copy the file to your child theme, keeping the same directory structure.

**Example: Modifying the post content template**

1.  The original file is at `wp-content/themes/portfoliocraft/template-parts/content/content-single.php`.
2.  To override it, copy this file to `wp-content/themes/portfoliocraft-child/template-parts/content/content-single.php`.
3.  Now you can safely edit the copied file in your child theme.

WordPress will automatically use the file from your child theme instead of the parent theme's file.

### 4.2. Header and Footer

The header and footer of the site are a special case. They are typically **not** controlled by the theme's `header.php` and `footer.php` files. Instead, they are controlled by the **Theme Builder** in the `rakmyat-core` plugin.

**How it works:**

1.  You create a header and a footer as `rmt-template` posts using Elementor.
2.  In the **Theme Options** panel, you assign these templates to the "Header" and "Footer" locations.
3.  The theme's `header.php` and `footer.php` files then contain logic to check for these options and render the selected Elementor template instead of their own content.

**To customize the header or footer:**

*   **DO NOT** edit `header.php` or `footer.php` directly, unless you want to fundamentally change how the templates are loaded.
*   **DO** go to `Templates` in the WordPress admin, find your active header or footer template, and edit it with Elementor.

---

## 5. The Demo Import System

PortfolioCraft uses the One-Click Demo Import (OCDI) plugin to allow users to import pre-built demo sites.

### 5.1. Configuration: `inc/demo-import/demo-config.php`

This file tells the OCDI plugin where to find your demo content.

*   `base_url`: The URL of the server where your demo content is stored.
*   `demos`: An array that defines the available demos. The system will first try to load a `demos.json` file from your server. If that fails, it will use the demos defined in this array as a fallback.

### 5.2. The Demo Import Process

When a user clicks "Import Demo", the following happens:

1.  **Content Import:** The importer fetches an XML file from your demo server and imports posts, pages, images, menus, etc.
2.  **Theme Options Import:** It fetches a `.json` or `.dat` file and imports the Redux theme options, setting up the colors, typography, and other settings to match the demo.
3.  **Widget Import:** It fetches a `.wie` or `.json` file and imports the widgets for the sidebars and footer.
4.  **Elementor Settings Import:** It can import Elementor's global site settings.
5.  **After Import Hook:** The OCDI plugin provides an `ocdi/after_import` action that you can use to run custom functions after the import is complete (e.g., setting the home page and posts page).

### 5.3. Best Practices for Demo Content

*   **Keep it clean:** Your demo content should be well-organized and free of any testing or draft posts.
*   **Optimize images:** The images in your demo content will be downloaded to the user's server. Ensure they are optimized for the web to make the import process faster.
*   **Use a remote server:** Host your demo content files on a reliable server (like an Amazon S3 bucket or your own web server). **DO NOT** package the demo content files inside the theme itself, as this will make the theme's zip file too large for ThemeForest.
*   **Test thoroughly:** Run the import process yourself from start to finish on a clean WordPress installation to ensure everything works as expected.

---

## 6. Theme vs. Plugin: A Clear Distinction

This is one of the most important concepts to understand.

| Feature / Responsibility | Handled by `portfoliocraft` (Theme) | Handled by `rakmyat-core` (Plugin) |
| :--- | :--- | :--- |
| **Styling & Design** | ✅ **Yes** (Colors, fonts, spacing, layout) | ❌ No |
| **Elementor Widgets** | ❌ No | ✅ **Yes** (All custom widgets are here) |
| **Theme Options Panel** | ✅ **Yes** (Defines the options) | ✅ **Yes** (Provides the Redux Framework) |
| **Template Files** | ✅ **Yes** (`page.php`, `single.php`, etc.) | ❌ No |
| **Theme Builder** | ❌ No | ✅ **Yes** (Provides the `rmt-template` CPT) |
| **Header/Footer Content** | ❌ No (Renders the template) | ✅ **Yes** (The templates themselves) |
| **Mega Menu** | ❌ No | ✅ **Yes** (The walker and the logic) |
| **Custom Post Types** | ❌ No | ✅ **Yes** (e.g., Portfolio, Team Members) |
| **Demo Import Config** | ✅ **Yes** (`demo-config.php`) | ❌ No |

**Rule of Thumb:**

*   If you are changing something that affects **how the site looks**, you should be working in the **theme**.
*   If you are changing something that affects **what the site does**, you should be looking in the **plugin**.

---

## 7. Performance Best Practices

*   **Use a Child Theme:** This is the #1 performance and maintenance best practice. It prevents you from losing your customizations when you update the parent theme.
*   **Image Optimization:** Before uploading any images to the media library, make sure they are properly sized and compressed. Use a plugin like Smush or ShortPixel to automate this.
*   **Caching:** Use a good caching plugin (like W3 Total Cache or WP Super Cache) to dramatically speed up your site.
*   **Asset Minification:** The theme's assets are already minified. If you add your own CSS or JavaScript in a child theme, make sure to minify it for production.
*   **Don't Overload `functions.php`:** If you have a lot of custom functions, consider organizing them into different files in your child theme and including them from your `functions.php`.
*   **Choose your plugins wisely:** Only use well-coded plugins from reputable authors. A single poorly coded plugin can bring your whole site to a crawl.

By following this documentation, you should be well-equipped to customize, manage, and maintain your PortfolioCraft-powered website. Happy coding!
