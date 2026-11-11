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
- [Matter](https://github.com/eighteen73/matter) 1.0+, for sections, schemes, menus, panels and site settings. It's a must-use plugin installed by Composer (`eighteen73/matter`), so it's always active and the theme uses it without checking. An admin notice warns if Composer has installed an older version or none.
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
| `config/allowed-blocks.php` | Blocks offered to editors (`namespace/*` wildcards), passed to Matter, which enforces it in the post editor. Templates may use any block. |
| `config/breakpoints.json` | The only breakpoint source: compiled into SCSS (`@use "pulsar:breakpoints"`) and passed to Matter. |
| `config/settings.php` | Appearance › Options fields (contact, social, header call to action, footer). |
| `config/matter.php` | Matter variants: behaviour and controls layout (e.g. carousel Single / Cards / Slideshow; gallery Thumbnails; archive list Four across / Feature first). The comments list every option. Styled in `src/css/blocks/matter-*.scss`. |
| `config/theme-json/` | Design tokens and editor settings. |
| `assets/svg/icons/` | The button icons editors can pick from (Matter registers them as the `pulsar` icon collection). Add an icon here only if clients should see it. |
| `assets/svg/ui/` | Interface icons used by CSS (`@include icon("ui/x")`) and PHP. Not shown to editors. |
| `assets/svg/brand/` | The site logo and the eighteen73 credit (`Pulsar\display_svg( 'brand/logo' )`). |
| `assets/svg/woocommerce/account/` | My Account navigation icons. |
| `src/blocks/` | Sections (`text`, `hero`, `media-text`, `cards` + `card`, `cta`). |
| `src/css/` | ITCSS: settings → tools → generic → elements → components → utilities. |
| `src/css/blocks/{namespace}-{block}.scss` | Per-block styles, loaded only when the block is on the page (`core-*`, `matter-*`, `pulsar-*`, `woocommerce-*`). |
| `template-parts/` | PHP parts, rendered in templates by Matter's `matter/template-part` block (files with a `Title:` header are offered in its settings): `site-header`, `site-footer`, checkout variants, and `header/` and `footer/` pieces. |
| `templates/` | Block templates. They use `<!-- wp:matter/template-part {"slug":"site-header"} /-->` for the header and footer. |
| `patterns/loop-item-product.php`, `patterns/loop-item.php` | How each product, and each post or item of any other post type, shows in every loop and carousel, through Matter's `matter/loop-item` block. Add `patterns/loop-item-{post_type}.php` for a post type that needs its own. |
| `patterns/loop-item-accordion.php`, `patterns/loop-item-tabs.php` | An item's content in an accordion or tabs of posts ("Posts accordion", "Posts tabs"): the excerpt with a link; the post's title is the heading or tab. Add `loop-item-{post_type}-accordion.php` (or `-tabs`) for a post type that needs its own, e.g. an FAQ's whole answer, and `loop-item-carousel.php` or `loop-item-{post_type}-carousel.php` for carousel slides unlike the post type's usual item. See Matter's README for the full order. |

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

Tokens come in two layers, and components only ever use the second:
- **Presets** are the raw scales: every colour, spacing step, font size, radius and shadow. They live in `config/theme-json/settings.*.json`.
- **Roles** say what a value is for: scheme colours, spacing roles, font weights. They live in `config/theme-json/settings.custom.json` and point at presets.

### Palette

`settings.color.json` holds every colour the site has, named by rank, not by lightness:
- `brand-primary`, then `-secondary`/`-tertiary` if the brand has them; `accent-primary`, `accent-secondary`… for supporting colours.
- `-subtle` (paler) and `-strong` (deeper) versions of each, written as `color-mix(in oklch, var(--wp--preset--color--brand-primary) 12%, white)` and `… 70%, black)`. Swap in a hex when the design specifies one. Mix towards white and black, not `base` and `contrast`: a tinted `contrast` shifts the hue.
- `neutral-50` to `neutral-950`, `base` (page background) and `contrast` (text).
- `status-error`, `status-warning`, `status-success`, `status-info`, each with `-subtle`; and `focus`.

### Schemes

`custom.scheme` defines `default`, `subtle`, `brand` and `contrast`. Every scheme sets all 11 roles, each pointing at the palette:

| Role | For |
| --- | --- |
| `background` | The section's background |
| `background-surface` | Things that sit on it and need to stand apart: cards, inputs, accordion panels |
| `background-fill` | Strong solid fills: buttons, badges, the active tab |
| `text` | Body text |
| `text-subtle` | Secondary text: dates, captions, help text |
| `text-heading` | Headings |
| `text-link` | Links |
| `text-fill` | Text and icons on `background-fill` |
| `border` | Dividers and card outlines |
| `border-strong` | Input borders and anything that must be seen (3:1) |
| `highlight` | Small accents: eyebrows, icons, active indicators |

Matter writes the `default` scheme onto `:root` as `--scheme--{role}` and swaps in another on `.has-scheme-{slug}`, so nested content adapts. Component CSS uses `var(--scheme--text-link)` and the like with no fallback: a missing role should look wrong in review. Read the palette directly only for one-off marks, such as text over a darkened image. In templates, put `has-scheme has-scheme-{slug}` on a wrapper, never palette colour classes; `.has-text-subtle` sets subtle text. Client-facing scheme names come from the `matter_scheme_labels` filter.

### Spacing, type and the rest

| Variable | Values |
| --- | --- |
| `--wp--preset--spacing--{slug}` | `xs` 4, `sm` 8, `md` 8→12, `lg` 12→16, `xl` 16→24, `2-xl` 24→32, `3-xl` 32→48, `4-xl` 48→64, `5-xl` 64→96, `6-xl` 96→128 (px, 320px → 1440px screens) |
| `--wp--custom--spacing--edge` | Page side padding, 24→96 |
| `--wp--custom--spacing--gutter` | Gaps between columns and grid items, 32→48 |
| `--wp--custom--spacing--section` | Padding above and below a section, 64→96 |
| `--wp--style--block-gap` | Space between blocks in a flow (`xl`), WordPress's own variable |
| `--wp--preset--font-size--{slug}` | `xs` 12, `sm` 14, `md` 16 (body), `lg` 19→20, `xl` 23→25, `2-xl` 28→31, `3-xl` 33→39, `4-xl` 40→49, `5-xl` 48→61 |
| `--wp--custom--typography--font-weight--{body,heading,label}` | 400, 700, and `label` (buttons, form labels, menus, tabs, prices) follows `heading` unless set |
| `--wp--custom--typography--line-height--{none,tight,normal}` | 1, 1.1, 1.5 |
| `--wp--preset--border-radius--{xs,sm,md,lg,full}` | 4, 8, 10→16, 16→24, pill; each multiplied by `--wp--custom--roundness` (0 square, 1 default, 1.5 rounder) |
| `--wp--preset--shadow--{xs,sm,md,lg}` | Tinted from `neutral-900` |
| `--wp--custom--transition--duration--{quick,normal}` | 150ms, 300ms |
| `--wp--style--global--content-size` / `--wide-size` | 768px / 1440px |

Headings map to sizes in `src/css/tools/mixins/_typography.scss`: `h1` `4-xl`, `h2` `3-xl`, `h3` `2-xl`, `h4` `xl`, `h5` `lg`, `h6` `md`. `5-xl` is free for display headings.

The fluid sizes come from [Utopia](https://utopia.fyi) with 320px and 1440px as the screen range: type at 16px body, ratio 1.2 → 1.25; spacing as one-up pairs (each step from the one below to its own size). Regenerate there rather than hand-writing a `clamp()`. Font sizes are `{min, max}` pairs in `settings.typography.json`, which WordPress turns into `clamp()`s using `settings.typography.fluid`; spacing sizes and roles are written as Utopia's `clamp()`s. If a font needs larger body text, change `md` to 16→18 and regenerate the steps above it.

The editor's colour, spacing, layout, border and typography controls are switched off in `theme.json`. Presets still generate their variables.

### Designer handover

Ask the designer for:
- one hex per colour, ranked by use: `brand-primary` at minimum, plus `base` and `contrast`. A paler or deeper version of a colour is its `-subtle` or `-strong`; only give a hex if the default mix isn't right. Greys always go in `neutral-*`.
- the schemes the site needs (usually two to four), each as the 11 roles above, picked from the palette.
- fonts and weights for body, headings and labels; body size if not 16px.
- roundness (square, default or rounder) and whether section spacing differs from 64→96.

Name the Figma variables the same way (`brand-primary`, `background-fill`, `spacing/xl`) so a design maps straight onto theme.json.

## CSS conventions

The aim is no `!important`.

**Specificity**
- One BEM class per selector. Nest only for a part, modifier or state (`&__title`, `&.is-style-feature`, `&.is-open`).
- Element styles (`h2`, `a`, `button`) stay at element specificity, or inside `:where()`, so any class beats them.
- Matter's skin is wrapped in `:where()`, so a single class here always overrides it.
- `!important` only to beat someone else's `!important` or inline style (WordPress's constrained layout, WooCommerce, Gravity Forms), or to guarantee accessibility (focus ring, visually hidden). Always with a `stylelint-disable-line` comment saying why.

**Class names**
- `.wp-block-{namespace}-{block}` and `__{element}` for its parts.
- `.is-style-{variant}` for variants, `.has-{option}` for options, `.is-{state}` for states, `.has-scheme-{slug}` for schemes.

**CSS variables**
- Use the tokens above: `--scheme--{role}` for colour, presets and roles for everything else. No hex values in component CSS.
- A component's own variables are `--{component}-{setting}`, and only for settings or values JavaScript sets. Matter's block settings (`--gallery-gap`, `--menu-duration`…) can be set on `:root` or on the block's class; see Matter's README.
- Private helpers a component computes for itself are `--_{component}-{name}`.
- A value used once is written as a property, not a variable.

**Buttons**
- Every button is the base plus a variant (`primary`, `secondary`, `link`) and optionally a size (`small`). The base draws the border on every variant and allows for it in the padding, so all buttons are the same height.
- `@include button(secondary, small)` makes something a button. Anything that's already one (`.wp-element-button`, `.wp-block-button__link`) only needs `@include button-variant(secondary)` or `button-size(small)`, which set colours or spacing through private `--_button-*` variables.
- Hovers mix the fill towards the section's background, so they work in every scheme.

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

**Megamenus** aren't styled in the starter. Matter supports them (a menu item's "Display submenu as a megamenu"); see Matter's README for setting one up, with an example panel and CSS.

## Archives, search and 404

`index.html` (blog, categories, tags, authors, dates), `archive-product.html`, `taxonomy-product_cat.html`, `search.html` and `404.html` each have fixed parts plus Matter's `matter/archive-content` around the list. The fixed parts are breadcrumbs, notices, subcategories and the search form, and they're hidden when empty. Editors add content above and below the list in Appearance › Archive content (see Matter's README).

Archives without their own content show a pattern, which is also where editors start when they customise one:
- `patterns/archive.php`: the archive's title and description, then the list;
- `archive-search.php`;
- `archive-404.php`.

Add `pulsar/archive-{post type}` (e.g. `archive-product.php`) to give a post type its own default. The list's variants (Three across, Four across, Feature first) are in `config/matter.php` and styled in `src/css/blocks/matter-archive-loop.scss`.

## Rendering blocks from PHP

```php
Pulsar\the_block( 'woocommerce/mini-cart' );
echo Pulsar\get_block( 'core/search', [ 'label' => 'Search' ] );
```

This works for dynamic blocks only. It runs them through core's render pipeline, so assets and Interactivity API processing work.

## WooCommerce

Shop, product, cart, checkout and account templates are block templates in `templates/`. Their styles are in `src/css/blocks/woocommerce-*.scss` and `src/css/woocommerce-account.scss`.
