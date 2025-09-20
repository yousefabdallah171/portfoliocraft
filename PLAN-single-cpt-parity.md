# Plan: Make Portfolio and Services single pages identical to Post single (no Elementor)

## Objective
Render `portfolio` and `services` single pages using the exact same structure, features, and styles as Post singles, without Elementor templates.

## Constraints
- No Elementor overrides. We rely on theme PHP templates.
- Minimal, safe edits to theme and CPT registration.

## Current behavior summary
- Post singles use `themes/portfoliocraf/single.php` + `template-parts/content/content-single.php`.
- Sidebar config in `single.php` uses `portfoliocraft()->get_sidebar_value('post')`, which maps to `sidebar-blog` only for `is_singular('post')`. CPTs don’t match, so they end up with no sidebar classes/content.
- CPTs (`portfolio`, `services`) are registered by filter with supports: `title`, `editor`, `thumbnail`, `elementor` only. They miss `comments`/`excerpt`/`author` and `post_tag` taxonomy, so parts of the post UI are absent.

## Changes required (in order)
1) Align CPT feature supports with posts
- File: `themes/portfoliocraf/inc/theme-options/rakmyat-theme-filters.php`
- In `portfoliocraft_add_post_type()`:
  - For both `portfolio` and `services` add to `args.supports`:
    - `comments`, `excerpt`, `author`
  - Also add post tags taxonomy:
    - `args['taxonomies'] => array('post_tag')`
- Impact: Enables comments block, excerpts where used, and tag list rendering in `content-single.php`.

2) Ensure sidebar parity for CPT singles
- File: `themes/portfoliocraf/inc/classes/class-main.php`
- In `get_sidebar_value($page = 'blog')`, extend the resolution so CPT singles are treated like posts:
  - Change the computation of `$sidebar_reg` to:
    - If `is_singular( array('post','portfolio','services') )` => `'blog'`
    - If WooCommerce single => `'shop'`, else fallback to `$page`
- Impact: CPT singles will use the same sidebar detection and classes as posts (and show the sidebar if active).

3) Explicitly route CPT single templates to post single (optional but robust)
- If you want explicit parity regardless of future files:
  - File: `themes/portfoliocraf/single-services.php` (new)
  - Content:
    ```php
    <?php require get_template_directory() . '/single.php';
    ```
  - If a `single-portfolio.php` file is present and differs, replace its content with the same one-liner above; if it does not exist, WordPress already falls back to `single.php`.
- Impact: Guarantees the same PHP template path as posts.

4) CSS parity (if required by selectors)
- File: `themes/portfoliocraf/assets/css/style.css`
- If you have rules scoped to `.single-post` only, mirror them for `.single-portfolio` and `.single-services`, or refactor selectors to target shared classes like `.rmt-single-post` that are already in the markup.
- Example (if needed):
  ```css
  /* Mirror single post styles for CPT singles */
  .single-portfolio .rmt-single-post,
  .single-services .rmt-single-post {
    /* same rules as .single-post .rmt-single-post */
  }
  ```

5) Permalinks
- After registration/support changes, go to Settings → Permalinks → Save (flush rewrite rules once).

## Validation checklist
- Portfolio and Services singles show:
  - Same header/title block and featured image as posts
  - Author/date/comments meta present
  - Tags section appears when tags are assigned
  - Sidebar presence and layout matches a post that uses the same sidebar setting
- No Elementor template is active for `single-portfolio` or `single-Services` (Theme Builder), so theme templates are used.
- Search and archive pages for CPTs continue to work (no regressions).

## Rollback notes
- If you need to revert, remove added supports/taxonomies and sidebar mapping changes, and delete `single-services.php`/adjust `single-portfolio.php` accordingly. Save permalinks again. 