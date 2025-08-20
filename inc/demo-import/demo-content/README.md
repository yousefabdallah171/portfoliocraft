# Demo Content Directory

This directory is for **LOCAL DEMOS** (Priority 1 in the system).

## How to Add Local Demos

Create folders here with demo files:

```
demo-content/
├── business/
│   ├── content.xml        ← Required: WordPress export file
│   ├── theme-options.json ← Optional: Redux theme options
│   ├── widgets.wie        ← Optional: Widget export
│   ├── customizer.dat     ← Optional: Customizer settings
│   ├── site-settings.json ← Optional: Elementor settings
│   └── screenshot.jpg     ← Optional: Preview image
├── portfolio/
│   ├── content.xml
│   └── preview.png
└── shop/
    ├── content.xml
    ├── theme-options.json
    └── widgets.wie
```

## Required Files

- **content.xml** - WordPress export file (REQUIRED)

## Optional Files

- **theme-options.json** - Redux Framework theme options
- **widgets.wie** - Widget export file
- **customizer.dat** - WordPress customizer settings
- **site-settings.json** - Elementor site settings
- **screenshot.jpg/png** - Preview image
- **info.json** - Custom demo info (title, description)

## Priority System

✅ **If demos exist here**: System uses local demos exclusively  
⚠️ **If this folder is empty**: System falls back to remote server URLs  

## Demo Info File (Optional)

Create `info.json` in each demo folder for custom titles/descriptions:

```json
{
    "title": "Business Pro Demo",
    "description": "Complete business website with contact forms and services"
}
```

## Getting Demo Files

### 1. Export from Existing Site
- **Content:** WordPress Admin → Tools → Export
- **Theme Options:** Redux Panel → Import/Export
- **Widgets:** Use "Widget Importer & Exporter" plugin
- **Customizer:** Use "Customizer Export/Import" plugin

### 2. From Theme Demo Sites
- Export content from your existing demo sites
- Save theme option backups
- Create widget exports

### 3. Manual Creation
- Create sample content
- Configure theme options
- Set up widgets and menus
- Export all components

## Local vs Remote Demos

**Local Demos (Priority 1):**
- ✅ Faster loading (no internet required)
- ✅ Better performance
- ✅ Full control over files
- ✅ No server dependency

**Remote Demos (Priority 2):**
- ⚠️ Requires internet connection
- ⚠️ Depends on server availability
- ⚠️ Slower loading times
- ✅ Easier to update centrally

**Best Practice:** Use local demos for better user experience!