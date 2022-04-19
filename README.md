<p align="center">
  <strong>WordPress starter theme with Tailwind CSS, Alpine JS and a modern development workflow</strong>
</p>

## Requirements

Make sure all dependencies have been installed before moving on:

- [WordPress](https://wordpress.org/) >= 5.9
- [PHP](https://secure.php.net/manual/en/install.php) >= 7.4.0
- [Composer](https://getcomposer.org/download/)
- [Node.js](http://nodejs.org/) >= 16

## Theme structure

```sh
themes/your-theme-name/    # → Root of your theme
├── app/                   # → Theme PHP
│   ├── Contracts/         # → Interfaces and Traits
│   ├── Tools/             # → Various tools used throughout the theme
│   ├── BlockPatterns.php  # → Block pattern management
│   ├── BlockStyles.php    # → Block styles management
│   ├── Enqueue.php        # → Theme assets
│   └── Setup.php          # → Theme setup
├── bootstrap/             # → Theme bootstrap and compatibility
│   ├── autoload.php       # → Theme autoloader
│   ├── compat.php         # → Theme compatibility
│   └── theme.php          # → Theme mini container
├── config/                # → Theme configuration files
│   ├── bindings.php       # → Theme container bindings
│   ├── block-patterns.php # → Theme compatibility
│   └── theme.php          # → Theme bootloader
├── parts/                 # → Theme partial template files
├── patterns/              # → Theme block pattern template files
├── composer.json          # → Autoloading for `app/` classes
├── public/                # → Built theme assets (never edit)
├── functions.php          # → Theme bootloader
├── node_modules/          # → Node.js packages (never edit)
├── package.json           # → Node.js dependencies and scripts
├── resources/             # → Theme assets and templates
│   ├── css/               # → Theme stylesheets
│   ├── fonts/             # → Theme fonts
│   ├── img/               # → Theme images
│   ├── js/                # → Theme javascript
│   ├── svg/               # → Theme SVGs
├── templates/             # → Theme custom template files
├── screenshot.png         # → Theme screenshot for WP admin
├── style.css              # → Theme meta information
├── vendor/                # → Composer packages (never edit)
```

## Theme setup

Edit `app/Setup.php` to enable or disable theme features, setup navigation menus, post thumbnail sizes, and sidebars.

## Theme development

- Run `npm install` from the theme directory to install dependencies
- Run `composer install` from the theme directory to create the Composer autoloader file

### Build commands

- `npm run watch` — Compile assets when file changes are made, start Browsersync session
- `npm run build` — Compile assets for production
