# Pulsar

<p>
  <strong>WordPress starter theme with Tailwind CSS, Alpine JS and a modern development workflow</strong>
</p>

## Requirements

Make sure all dependencies have been installed before moving on:

- [WordPress](https://wordpress.org/) >= 5.9
- [PHP](https://secure.php.net/manual/en/install.php) >= 7.4.0
- [Node.js](http://nodejs.org/) >= 16

## Theme structure

```sh
themes/your-theme-name/     # → Root of your theme
├── blocks/                 # → Custom blocks
├── config/                 # → Theme configuration files
│   ├── bindings.php        # → Theme container bindings
│   ├── block-patterns.php  # → Custom block patterns
│   ├── block-styles.php    # → Custom block styles
│   └── mix.json            # → Mix entries and other settings
├── includes/               # → Theme functions and classes
│   └── classes/            # → Theme classes (autoloaded)
│       ├── Contracts/      # → Interfaces and Traits
│       ├── Editor/         # → Editor specific classes
│       ├── Blocks.php      # → Registration of custom blocks
│       ├── Patterns.php    # → Block pattern management
│       ├── Styles.php      # → Block styles management
│       └── Tools/          # → Various tools used throughout the theme
│           ├── Mix.php     # → Handles asset management with Laravel Mix
│           └── Svg.php     # → Allows manipulating SVGs and inlining them
│       ├── Enqueue.php     # → Theme assets
│       └── Setup.php       # → Theme setup
│   └── template-tags/      # → Theme template tags
│   ├── autoload.php        # → Theme autoloader
│   ├── compat.php          # → Theme compatibility
│   └── theme.php           # → Theme mini container
├── dist/                   # → Built theme assets (never edit)
├── parts/                  # → Theme partial template files
├── patterns/               # → Theme block pattern template files
├── node_modules/           # → Node.js packages (never edit)
├── src/                    # → Theme assets and templates
│   ├── css/                # → Theme stylesheets
│   ├── fonts/              # → Theme fonts
│   ├── img/                # → Theme images
│   ├── js/                 # → Theme javascript
│   ├── svg/                # → Theme SVGs
├── templates/              # → Theme custom template files
├── functions.php           # → Theme bootloader
├── package.json            # → Node.js dependencies and scripts
├── screenshot.png          # → Theme screenshot for WP admin
├── style.css               # → Theme meta information
```

## Theme setup

Edit `app/Setup.php` to enable or disable theme features, setup navigation menus, post thumbnail sizes, and sidebars.

## Theme development

- Run `npm install` from the theme directory to install dependencies

### Build commands

- `npm run watch` — Compile assets when file changes are made, start Browsersync session
- `npm run build` — Compile assets for production
