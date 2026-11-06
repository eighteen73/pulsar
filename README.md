# Pulsar

**eighteen73's starter block theme.** Copy it for each project, then restyle, extend or delete what's here.

Pulsar 6 is built around one idea: **clients edit content; developers own layout.**

- **Clients** build pages from **sections** (Hero, Text, Media and text, Cards, Call to action…) and fill them with core content blocks: headings, paragraphs, lists, buttons, images and video.
- **Clients choose** a colour scheme and a variant, never colours, spacing or widths.
- **Header and footer** are PHP, so they stay in version control. Clients edit menus (Appearance › Menus) and site-wide content (Appearance › Options).
- **The Site Editor** is only available on local/development environments (via Orbit).

## Requirements

- [WordPress](https://wordpress.org/) 7.1+
- [PHP](https://www.php.net/) 8.3+
- [Node.js](https://nodejs.org/) 20+
- [Matter](https://github.com/eighteen73/matter) 1.0+, for sections, schemes, menus, panels and site settings. An admin notice warns if it's missing.
- [Orbit](https://github.com/eighteen73/orbit), recommended. It disables the Site Editor and ignores database template overrides outside development.

## Development

```bash
npm install
npm run start   # watch
npm run build   # production build (commit build/ and theme.json)
npm run lint:css && npm run lint:js && npm run lint:php
```

`theme.json` is generated from `config/theme-json/*.json`. Edit the parts, not `theme.json`.

## Where things live

| Path | What |
| --- | --- |
| `config/allowed-blocks.php` | Blocks offered to editors (`namespace/*` wildcards). Templates may use any block. |
| `config/breakpoints.json` | The only breakpoint source: compiled into SCSS (`@use "pulsar:breakpoints"`) and passed to Matter. |
| `config/settings.php` | Appearance › Options fields (contact, social, header call to action, footer). |
| `config/matter.php` | Matter variants: behaviour and controls layout (e.g. carousel Single / Cards / Slideshow, plus reference Hero, Logo ticker and Peek styles; gallery Thumbnails). Styled in `src/css/blocks/matter-*.scss`. |
| `config/theme-json/` | Design tokens and editor settings. |
| `src/blocks/` | Sections (`text`, `hero`, `media-text`, `cards` + `card`, `cta`) and `template-part`. |
| `src/css/` | ITCSS: settings → tools → generic → elements → components → utilities. |
| `src/css/blocks/{namespace}-{block}.scss` | Per-block styles, loaded only when the block is on the page (`core-*`, `matter-*`, `pulsar-*`, `woocommerce-*`). |
| `template-parts/` | PHP parts: `site-header`, `site-footer`, checkout variants, and `header/`, `footer/`, `megamenu/` pieces. |
| `templates/` | Block templates. They use `<!-- wp:pulsar/template-part {"slug":"site-header"} /-->` for the header and footer. |

## Sections

A section is a block with named content slots. Its layout lives in code, so changing a section updates every page that uses it.

**To add one:**

1. Create `src/blocks/{name}/block.json` with `supports.matter.slots`. Copy an existing section.
2. Optionally add `render.php` when the markup needs it, keeping the classes Matter uses in the editor (see the Matter README).
3. Style it in `src/css/blocks/pulsar-{name}.scss`. Shared section rules are in `components/_sections.scss`.
4. Run `npm run build`. Sections appear in the inserter's **Sections** category.

**Slots:**
- `allowedBlocks` limits what editors can add.
- `templateLock: "contentOnly"` fixes the blocks and lets editors change only their content.
- **Append** new slots: existing pages pick them up the next time they're edited.

## Colour schemes and tokens

Schemes are defined in `config/theme-json/settings.custom.json` under `custom.scheme`: `default`, `tint`, `brand` and `contrast`. Each scheme sets these tokens:
- background
- text
- heading
- muted
- link
- border
- border-strong
- button-background
- button-text

Matter maps the active scheme onto `--scheme--{token}`. Elements, buttons, links and sections read those tokens, so nested content adapts automatically.

**Token reference:**

| Variable | Use |
| --- | --- |
| `--wp--preset--color--{slug}` | Palette: brand-primary, brand-secondary, accent-primary/secondary/tertiary, tint-primary/secondary, contrast, base |
| `--scheme--{token}` | Colours for the current scheme (prefer these in component CSS) |
| `--wp--custom--color--neutral--{50–900}` | Neutral ramp |
| `--wp--custom--color--{error,warning,success}` / `…-surface` | Feedback colours |
| `--wp--custom--color--focus`, `--wp--custom--color--focus-contrast`, `--wp--custom--color--shadow` | Focus ring, shadows |
| `--wp--preset--spacing--{xs…9-xl}` | Spacing scale |
| `--wp--custom--spacing--{edge,block-gap,section-gap,row-gap,column-gap}` | Layout spacing roles |
| `--wp--preset--font-size--{xs…5-xl}` | Type scale |
| `--wp--custom--typography--{font-weight,line-height,letter-spacing}--*` | Type details |
| `--wp--preset--border-radius--*`, `--wp--custom--global--is-rounded` | Radii (multiply by `is-rounded`) |
| `--wp--custom--transition--{duration,timing}--*` | Motion |

The editor's colour, spacing, layout, border and typography controls are switched off in `theme.json`. Presets still generate their variables.

## Header, footer and menus

`template-parts/site-header.php` contains:
- the logo;
- the primary menu in a Matter panel: a drill-down drawer below `lg`, an inline dropdown from `lg`;
- the header call to action;
- the mini-cart.

`site-footer.php` contains:
- the logo;
- contact details and social links (from Appearance › Options);
- the footer and legal menus;
- the copyright (`{year}` and `{site}` placeholders);
- the credit.

Menu locations: `primary`, `footer-1`, `footer-2` and `legal`.

**Megamenus.** In Appearance › Menus, tick "Display submenu as a megamenu" on a top-level item: its second-level items become column headings (use `#` links) and third-level items their links. Optionally attach a panel from `template-parts/megamenu/`: each PHP file with a `Title:` header is offered in the item's "Megamenu panel" list, and receives the menu item as `$args['item']`. `megamenu/contact.php` is an example: it shows the item's description, contact details and the header call to action from Appearance › Options.

## Rendering blocks from PHP

```php
Pulsar\the_block( 'woocommerce/mini-cart' );
echo Pulsar\get_block( 'core/search', [ 'label' => 'Search' ] );
```

This works for dynamic blocks only. It runs them through core's render pipeline, so assets and Interactivity API processing work.

## WooCommerce

Shop, product, cart, checkout and account templates are block templates in `templates/`. Their styles are in `src/css/blocks/woocommerce-*.scss` and `src/css/woocommerce-account.scss`.
