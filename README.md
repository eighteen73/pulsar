# Pulsar

**eighteen73's starter block theme.** Copy it for each project, then restyle, extend or delete what's here.

Pulsar 6 is built around one idea: **clients edit content; developers own layout.**

- **Clients** build pages from **sections** (Hero, Text, Media and text, Cards, Call to action…) and fill them with core content blocks: headings, paragraphs, lists, buttons, images and video.
- **Clients choose** a colour scheme and a variant, never colours, spacing or widths.
- **Header and footer** are PHP, so they stay in version control. Clients edit menus (Appearance › Menus) and site-wide content (Appearance › Site details).
- **The Site Editor** is only available on local/development environments (via Orbit).

## Requirements

- [WordPress](https://wordpress.org/) 7.1+
- [PHP](https://www.php.net/) 8.3+
- [Node.js](https://nodejs.org/) 26+ (the LTS; `.nvmrc`)
- [Matter](https://github.com/eighteen73/matter) 1.0+, for sections, schemes, menus, panels and site settings. It's a must-use plugin installed by Composer (`eighteen73/matter`), so it's always active and the theme uses it without checking. An admin notice warns if Composer has installed an older version or none.
- [Orbit](https://github.com/eighteen73/orbit), recommended. Outside local and development environments it removes the Site Editor, blocks REST writes to templates, template parts and global styles, and ignores any saved in the database, so the theme's files are always what shows. **Without Orbit**, editors with `edit_theme_options` can open the Site Editor and change templates, the header and footer template blocks, and global styles, and those database copies then override the theme's files.

## Development

```bash
npm install
npm run start   # watch
npm run build   # production build, emptying build/ first (commit build/ and theme.json)
npm run lint:css && npm run lint:js && npm run lint:php
```

`theme.json` is generated from `config/theme-json/*.json`. Edit the parts, not `theme.json`.

**In a Nebula project** the theme is an npm workspace of the project: run `npm install` at the project root, not in the theme (the theme's lock file isn't used there). Build and lint from the theme folder, or with `-w pulsar` from the root. A project's pre-commit hooks should run the theme's own linters from the theme folder (`npm run lint:css` and the rest): its stylelint config is an ES module that an older root stylelint can't load, and its PHPCS ruleset differs from the project's.

**Taking starter changes into a project.** A project's theme is a copy, but it can take later starter fixes as a git merge, so the project's own changes stay. When the theme is first copied, commit the untouched starter on its own branch (e.g. `pulsar-upstream`). To update, check that branch out, replace the theme folder with the new starter, commit, and merge the branch into the project's. Git combines the two sides and leaves only real clashes to resolve. Don't merge `build/` or `theme.json`: take either side, then build. Restart wp-env afterwards (`npm run env:stop`, then start): checking out the other branch replaces files it mounts one by one (`tests/wp-env/matter-loader.php`), and the sites fail with a critical error until it's restarted. Some starter tests check the starter's own design (the footer's gap, for one); where the project's design differs, change the test to match it.

### Browser tests

Playwright and axe tests (`tests/e2e`) run against [wp-env](https://www.npmjs.com/package/@wordpress/env) (Docker): WordPress, WooCommerce, this theme, and Matter from the project around it (`../../mu-plugins/matter`). There are two sites, each with its own containers and database: a development site to look around (`.wp-env.json`, http://localhost:8890) and the test site (`.wp-env.tests.json`, http://localhost:8891). Keep the two configs alike apart from their ports. Before the tests run, `tests/e2e/content.js` creates their content on the test site: a page of every section in each colour scheme, menus, Appearance › Site details values, posts and products.

```bash
npm run env:start                  # the development site
npm run env:start:tests            # the test site (the tests start it if it isn't running)
npm run env:stop                   # both
npx playwright install chromium    # once
npm run test:e2e                   # editor, sections in each scheme (axe), header, footer, archives, WooCommerce
```

They check that every section can be added in the editor, that pages pass axe (WCAG 2.2 AA) in every scheme, that every scheme's colour pairs meet AA contrast (`contrast.spec.js`), and that the header, footer, menus, archives, search, 404 and shop work. WooCommerce is covered from product filters, stock and reviews through the basket, coupons and checkout errors to an order received, and My Account. CI runs them against Matter's `release` branch, or Matter's `next` for Pulsar's `next` (`.github/workflows/lint.yml`; run it by hand to choose another Matter branch).

**Ports.** Every project copied from Pulsar uses 8890 and 8891. To run two at once, give one other ports in its git-ignored overrides, `.wp-env.override.json` (`{ "port": 8892 }`) and `.wp-env.tests.override.json` (`{ "port": 8893 }`); its tests use the test override's port. Before anything runs, the set-up checks that the site is this project's own test site and stops if it isn't, so the tests (which replace their content) never run against another project's.

**WooCommerce's paid extensions** (Subscriptions, with All Products for Subscriptions; Product Bundles; Bookings; Memberships) have their own tests, `tests/e2e/extensions.spec.js`. They need the plugins, so they run locally and skip in CI. With the extensions in the project's `web/app/plugins`:

```bash
cp tests/wp-env/extensions.override.example.json .wp-env.tests.override.json   # git-ignored
npm run env:start:tests
npm run test:e2e   # seeds a customer, products, a subscription, booking and membership (tests/wp-env/seed-extensions.php)
```

Delete `.wp-env.tests.override.json` and start the test site again to go back to WooCommerce alone (add a `port` to the copy if the project needs one). Subscriptions includes All Products for Subscriptions; the standalone plugin isn't mapped, as its first activation from WP-CLI fails alongside it.

**In a project,** adapt the tests as the theme changes, so a change to a section or a scheme is checked like the starter's:
- sections: add them to `sectionsPage()` in `content.js` (the page of every section, made in each scheme) and to `SECTIONS` in `editor.spec.js`; remove the starter's that the project deletes;
- schemes: `SCHEMES` in `content.js` (`contrast.spec.js` reads them from `theme.json` itself);
- Appearance › Site details, menus and the header and footer: `SETTINGS` and the menus in `content.js`, and the header and footer tests in `front-end.spec.js` (e.g. removing the header call to action means removing its test).
- the project's own post types and taxonomies (e.g. from a must-use plugin): the test site maps only the theme and Matter, so map the plugin in `.wp-env.tests.override.json` (`mappings`) and the dev config's override, or the tests can't see them.

**Updating Matter** in a project that uses a clone or copy of it: update the folder in place (`git pull`, or `rsync --delete`). Deleting and copying it again leaves the running wp-env pointing at the deleted folder, and the sites fail until `npm run env:stop` and start.

## Where things live

| Path | What |
| --- | --- |
| `config/allowed-blocks.php` | Blocks offered to editors (`namespace/*` wildcards), passed to Matter, which enforces it in the post editor. Templates may use any block. |
| `config/breakpoints.json` | The only breakpoint source: compiled into SCSS (`@use "pulsar:breakpoints"`) and passed to Matter. |
| `config/settings.php` | Appearance › Site details fields (contact, social, header call to action, footer). It's read after `init`, so a `select`'s `options` can come from a function, e.g. a taxonomy's terms. |
| `config/matter.php` | Matter variants: behaviour and controls layout (e.g. carousel Single / Cards / Slideshow; gallery Thumbnails; archive list Four across / Feature first). The comments list every option. Styled in `src/css/blocks/matter/`. |
| `config/theme-json/` | Design tokens and editor settings. |
| `assets/svg/icons/` | The button icons editors can pick from (Matter registers them as the `pulsar` icon collection). Add an icon here only if clients should see it. Draw them single-colour, as filled `path` or `polygon` elements only: WordPress's icon registry keeps only those (and `fill`, `fill-rule`, `d`, `transform`), and drops strokes, circles, groups and `fill="none"` without a warning, so an outline icon comes out blank. Draw a ring as two arcs with `fill-rule="evenodd"`. |
| `includes/classes/Matter.php` | Feeds `config/` into Matter, and names the theme's namespace, `pulsar` (`matter_theme_namespace`): the prefix Matter looks for loop item patterns under (`pulsar/loop-item…`) and the icon collection's name. Keep it `pulsar` even if the theme's folder is renamed or a child theme is added; icon names and pattern slugs depend on it. |
| `assets/svg/ui/` | Interface icons used by CSS (`@include icon("ui/x")`) and PHP. Not shown to editors. |
| `assets/svg/brand/` | The site logo and the eighteen73 credit (`Pulsar\display_svg( 'brand/logo' )`). `Pulsar\site_logo()` shows `brand/logo` in the header and footer. For a logo that's only a PNG (or JPEG, WebP, AVIF), put it in `assets/img/brand/` and pass its path with the extension, `site_logo( [ 'logo' => 'img/brand/logo.png' ] )` (in `template-parts/header/logo.php` and `footer/logo.php`): it's output as an `<img>` with its size, and a `logo@2x.png` beside it is used on high-density screens. An SVG is better where there is one: it's sharp at any size and can take the scheme's colour. |
| `assets/svg/woocommerce/account/` | My Account navigation icons. |
| `assets/img/` | Images the theme uses as they are: raster logos (`img/brand/`), and fixed-colour artwork such as multi-colour illustrations (`img/illustrations/`), shown with `<img>` or `background-image`. Single-colour icons go in `assets/svg/ui/` instead, so they take the scheme's colours. |
| `src/blocks/` | Sections (`text`, `hero`, `media-text`, `cards` + `card`, `cta`). |
| `src/css/` | ITCSS: settings → tools → generic → elements → components → patterns (a pattern's own styles) → third-party (non-block plugins) → utilities. `app.scss` is the front end; `editor.scss` the same for the editor. |
| `src/css/blocks/{namespace}/{block}.scss` | Per-block styles, loaded only when the block is on the page: `core/`, `matter/`, `pulsar/`, `woocommerce/`, e.g. `matter/gallery.scss` for `matter/gallery`. A new file needs no registering; a block whose styles build on another's is listed in `Enqueue::STYLE_DEPENDENCIES` (the gallery's carousel layout loads `matter/carousel.scss`). |
| `template-parts/` | PHP parts, rendered in templates by Matter's `matter/template-part` block (files with a `Title:` header are offered in its settings): `header` and `footer` (the parts in `header/` and `footer/` are their pieces), and checkout variants. |
| `templates/` | Block templates. They use `<!-- wp:matter/template-part {"slug":"header"} /-->` for the header and footer. |
| `patterns/loop-item-product.php`, `patterns/loop-item.php` | How each product, and each post or item of any other post type, shows in every loop and carousel, through Matter's `matter/loop-item` block. Add `patterns/loop-item-{post_type}.php` for a post type that needs its own. |
| `patterns/loop-item-accordion.php`, `patterns/loop-item-tabs.php` | An item's content in an accordion or tabs of posts ("Posts accordion", "Posts tabs"): the excerpt with a link; the post's title is the heading or tab. Add `loop-item-{post_type}-accordion.php` (or `-tabs`) for a post type that needs its own, e.g. an FAQ's whole answer, and `loop-item-carousel.php` or `loop-item-{post_type}-carousel.php` for carousel slides unlike the post type's usual item. See Matter's README for the full order. |

## Sections

A section is a block with named content slots. Its layout lives in code, so changing a section updates every page that uses it.

**To add one:**

1. Create `src/blocks/{name}/block.json` with `supports.matter.slots`. Copy an existing section.
2. Declare its variants as block styles, `styles` in `block.json` (see `media-text`): the default first, with `isDefault`, then one entry per variant. Each is styled as `.is-style-{name}`; the default has `is-style-default` too (Matter adds it on the site and in the editor), so its own styles don't need to exclude the others.
3. Optionally add `render.php` when the markup needs it, keeping the classes Matter uses in the editor (see the Matter README).
4. Style it in `src/css/blocks/pulsar/{name}.scss`. Shared section rules are in `components/_sections.scss`.
5. Run `npm run build`. Sections appear in the inserter's **Sections** category.

**Variants, not options.** A client chooses a section's scheme and one variant, nothing else, so each layout is its own variant, mirror images included ("Image last"). When a section's variants pass five or six, or split into families that share little CSS, make it two sections. A variant changes CSS only: every variant has the same slots, templates and locks, so a layout that needs different slots (two images where the others have one) is a separate section. Keep variant labels to about twelve characters ("Over image", "Faded"): the Styles panel shows them as two buttons a row and cuts longer ones off.

**To remove one,** delete its `src/blocks/{name}` folder and its stylesheet, and build: the build empties `build/` first, so it's no longer registered. Take it out of the tests too (see Browser tests).

**Slots:**
- `allowedBlocks` limits what editors can add.
- `templateLock: "all"` fixes the blocks (the hero's and card's single image) while leaving their settings, such as the focal point, in reach. Don't use `"contentOnly"`: it hides those settings behind "Edit pattern" (see Matter's README).
- Slots are matched by name, so they can be added, reordered or removed in any position. To rename one, list its old name (`"previously": [ "media" ]`) so existing content follows it. See Matter's README.
- `alignments` lists the alignments the slot's blocks offer. Text's content slot offers wide and full: a carousel, gallery, image, video or embed aligned wide takes the section's wide width, and one aligned full the whole band, edge to edge (`blocks/pulsar/text.scss`). Copy those rules for another section whose slot allows them.

**Spacing:**
- A full-width section is a band: its spacing is padding (`--wp--custom--spacing--section`, above and below), so its scheme's background covers it. Two bands in a row without a scheme read as one, so the second loses its top padding (`components/_sections.scss`). That relies on the first keeping its bottom padding: a section that runs to its edges (an edge-to-edge hero) should still keep the section spacing below it, or the next section will touch it.
- A page whose last section (or template band) has a scheme meets the footer, with no gap between them; other pages keep the flow gap above the footer (`components/_main.scss`).
- The shared padding is at zero specificity, so a section or variant sets its own with one class (`.wp-block-pulsar-cta { padding-block: … }`). Margins are different: the "sections touch" rule has to beat WordPress's layout margins, so a section that changes its own margin (pulling itself up into the band above) needs `.is-section` in its selector as well (`.wp-block-pulsar-{name}.is-style-{variant}.is-section`).
- Blocks in a slot are spaced by the block gap. For a different gap in one slot, set `--slot-gap` on the slot's class (`.wp-block-pulsar-cards__items { --slot-gap: 0; }`). Don't write a `> * + *` margin rule: it ties with the shared one, which loads later and wins. Each slot starts from the block gap, so the setting doesn't reach slots nested inside.

**Slots hidden at some widths:** in the editor, breakpoints follow the canvas, which is often narrower than the screen it stands for (the sidebars take the rest), so a slot hidden below `lg` can vanish there and can't be filled. Hide it with `@include hidden-on-site;` inside the media query: hidden on the site, always shown in the editor.

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

`custom.scheme` defines `default`, `subtle`, `brand` and `contrast`. The slugs and the number of schemes are the project's to choose (add an `accent`, rename `contrast`); Matter uses whatever is there, `default` on `:root`. The starter names `subtle` in `parts/breadcrumbs.html`, `templates/page-cart.html`, `templates/page-checkout.html` and `patterns/order-confirmation.php`, and the tests list the schemes in `tests/e2e/content.js`, so update those with a rename. Every scheme sets all 11 roles, each pointing at the palette:

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

**A scheme that changes with the screen width** (a header bar that's one scheme on phones and another from `lg`) can't be a class. Use the `scheme()` mixin inside media queries; it sets the roles from WordPress's variables for that scheme, as `.has-scheme-{slug}` does:

```scss
.site-header__bar {
	@include scheme(accent);

	@media (--lg) {
		@include scheme(subtle);
	}
}
```

It paints the background and text too, as the class does. `scheme(accent, $paint: false)` sets only the roles, for a band that paints its own background (a gradient that fills part of it with the scheme's colour) or uses a scheme for its content only.

**Contrast.** Each scheme's roles must pass WCAG AA in these pairs, which `tests/e2e/contrast.spec.js` checks for every scheme in `theme.json`:

| Foreground | On | Ratio |
| --- | --- | --- |
| `text`, `text-subtle`, `text-heading`, `text-link` | `background` and `background-surface` | 4.5:1 |
| `text-fill` | `background-fill` | 4.5:1 |
| `border-strong`, `highlight` | `background` and `background-surface` | 3:1 |

Links on a tinted background are the usual failure, which is why `subtle` uses `accent-primary-strong` for `text-link` and `highlight`: keep that reason when swapping colours, and check the surface as well as the background (cards and panels sit on `background-surface`).

### Spacing, type and the rest

| Variable | Values |
| --- | --- |
| `--wp--preset--spacing--{slug}` | `xs` 4, `sm` 8, `md` 8→12, `lg` 12→16, `xl` 16→24, `2-xl` 24→32, `3-xl` 32→48, `4-xl` 48→64, `5-xl` 64→96, `6-xl` 96→128 (px, 320px → 1440px screens) |
| `--wp--custom--spacing--edge` | Page side padding, 24→96 |
| `--wp--custom--spacing--gutter` | Gaps between columns and grid items, 32→48 |
| `--wp--custom--spacing--section` | Padding above and below a section, 64→96 |
| `--wp--style--block-gap` | Space between blocks in a flow (`xl`), WordPress's own variable |
| `--wp--preset--font-size--{slug}` | `xs` 12, `sm` 14, `md` 16 (body), `lg` 19→20, `xl` 23→25, `2-xl` 28→31, `3-xl` 33→39, `4-xl` 40→49, `5-xl` 48→61 |
| `--wp--custom--typography--font-weight--{body,heading,label}` | 400, 700, and `label` (buttons, form labels, menus, tabs, prices) follows `heading` unless set. If headings aren't bold, set `label` (usually 700), or every label goes to the heading's weight |
| `--wp--custom--typography--{font-family,letter-spacing,text-transform}--label` | The body font, `normal`, `none`: label text for controls (buttons, the header's menu, tabs, pagination, account navigation) through the `label-text` mixin; footer menus are body text. Set them for labels in their own font, capitals or tracking. Prices, totals and form labels use only the label weight |
| `--wp--custom--typography--line-height--{none,tight,normal}` | 1, 1.1, 1.5 |
| `--wp--preset--border-radius--{xs,sm,md,lg,full}` | 4, 8, 10→16, 16→24, pill; `xs` to `lg` are multiplied by `--wp--custom--roundness` (0 square, 1 default, 1.5 rounder). `full` is always a pill or circle (round controls such as carousel arrows stay round on a square site); use `lg` for anything that should square up with the rest |
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
- fonts and weights for body, headings and labels (and whether labels are in capitals or tracked); body size if not 16px.
- roundness (square, default or rounder) and whether section spacing differs from 64→96.

Name the Figma variables the same way (`brand-primary`, `background-fill`, `spacing/xl`) so a design maps straight onto theme.json.

## CSS conventions

The aim is no `!important`, and styles that are easy to find. Stylelint enforces the rules marked ✓.

**Selectors**
- ✓ Every class is written out in full: no `&__` or `&-` concatenation, so searching for a class finds its styles.
- ✓ Nesting only for the same element: `&:hover`, `&::before`, `&[aria-selected="true"]`, `&.is-open`, and at-rules (`@media`, `@include`). Anything else is its own rule with its full selector.
- ✓ Properties in alphabetical order. So set longhands rather than a shorthand plus an override: the override would sort before the shorthand, which then resets it (write the four `border-*-radius` corners, not `border-radius: 0` and one corner).
- ✓ ASCII only. Sass writes a non-ASCII character as it is and then starts the file with a byte order mark; WordPress inlines small block stylesheets, so the mark joins the first selector, which never matches. Escape characters (`content: "\201C"` or `open-quote`). `npm run lint:build` checks the built files; stylelint doesn't.
- Mixins output declarations; the caller writes the selector (`.thing:focus-visible { @include focus-ring; }`). The exception is `hidden-on-site`, which wraps the caller's selector to leave the editor out.
- Labels: `label-text` is label text on controls (buttons, the header's menu, tabs); `form-label` is a form field's label.

**Specificity**
- One class per selector where possible; context that's only there to scope goes in `:where()` (`:where(.site-header__navigation) .matter-menu__link`).
- ✓ At most 0,3,1, and no IDs. (WooCommerce and Gravity Forms files are exempt until they're rewritten.)
- Element styles (`h2`, `a`, form fields) stay at element specificity, or inside `:where()`, so any class beats them.
- A section's base rules for what's inside its slots put the context in `:where()`: `.wp-block-pulsar-card :where(.wp-block-pulsar-card__media) img` (0,1,1). A variant then overrides it with one class more, `.wp-block-pulsar-card.is-style-icon :where(.wp-block-pulsar-card__media) img` (0,2,1), and stays under the limit.
- Rules that pick a slot's contents by position (`figure + figure`, `:nth-child(even)`) soon pass the limit or the four-selector one. Put the position in `:where()` on every rule in the family, not only the long ones (`:where(.wp-block-pulsar-media-text__media figure + figure) + figure`): wrapping only some leaves them weaker than the rules they should override, which looks right in the source and wrong in the browser.
- `!important` only to beat someone else's `!important` or inline style (WordPress's constrained layout, WooCommerce, Gravity Forms), or to guarantee accessibility (focus ring). Always with a `stylelint-disable-line` comment saying why.

**In the editor.** Sections share their markup and CSS with the editor, but the editor adds its own styles to it (Matter's README, "In the editor"). In short: decorate a section's or card's `::before`, or an inner element, not its own `::after`, which the editor uses for block outlines; place things against the section from the slot, not a block inside it; and keep a slot that sits behind the content reachable (`.is-empty`, `pointer-events`).

**Matter's blocks**
- Matter ships a minimal default skin in `:where()`, so any class here overrides it. Pulsar styles every Matter block it uses in full (`blocks/matter/`, `components/_menu.scss`, the panel in `components/_header.scss`), icons included (`assets/svg/ui/`), so a Matter update doesn't change how the site looks.
- Where Pulsar's design differs from the skin, undo the skin's part explicitly (e.g. the selected tab sets `text-decoration: none` over Matter's underline).
- Matter shows and hides parts per mode (a menu's toggle text, submenus and their headers, the panel toggle): don't set `display` on these.
- Matter's per-mode classes exist only inside Matter's media queries, so mode-specific styles use the same breakpoint as the PHP that sets it (`collapse_below: 'lg'` in `template-parts/header.php` → `@media (--lg-down)`; `dropdownBelow` in `config/matter.php` → `@media (--md-down)`).

**Class names**
- `.wp-block-{namespace}-{block}` and `__{element}` for its parts.
- `.is-style-{variant}` for variants, `.has-{option}` for options, `.is-{state}` for states, `.has-scheme-{slug}` for schemes.

**CSS variables**
- Use the tokens above: `--scheme--{role}` for colour, presets and roles for everything else. No hex values in component CSS. Icons and form controls are drawn in the scheme's colours (masks, gradients), never fixed-colour SVGs.
- A component's own variables are `--{component}-{setting}`, and only for settings or values JavaScript sets. Matter's block settings (`--carousel-per-view`, `--gallery-gap`…) can be set on `:root` or on the block's class; see Matter's README. Don't invent variables in Matter's names (`--carousel-*`, `--gallery-*`…): Pulsar's private ones are `--_{component}-{name}`.
- A value used once is written as a property, not a variable.

**Buttons**
- Every button is the base plus a variant (`primary`, `secondary`, `link`) and optionally a size (`small`). The base draws the border on every variant and allows for it in the padding, so all buttons are the same height.
- `@include button(secondary, small)` makes something a button. Anything that's already one (`.wp-element-button`, `.wp-block-button__link`) only needs `@include button-variant(secondary)` or `button-size(small)`, which set colours or spacing through private `--_button-*` variables.
- Icons before a button's label are `::before`, and decoration after it `::after`. Some things that are buttons already use a pseudo-element: the cart link draws its bag with `::after` (`wc-cart-icon`), and WooCommerce's icon-only buttons (the mini-cart drawer's close) have the button classes. If every button gains decoration, leave those out.
- Hovers mix the fill towards the section's background, so they work in every scheme. That's the starter's default, not a rule: follow the design's hover, as long as it works in every scheme.

## Header, footer and menus

`template-parts/header.php` contains:
- the logo;
- the main menu in a Matter panel: a drill-down drawer below `lg`, an inline dropdown from `lg`;
- the header call to action;
- the mini-cart.

`footer.php` contains:
- the logo;
- contact details and social links (from Appearance › Site details);
- the footer and legal menus;
- the copyright (`{year}` and `{site}` placeholders);
- the credit.

Menu locations: `main`, `footer-1`, `footer-2` and `legal`.

**Footer menu headings** aren't in the starter. If a design heads each footer menu ("Help", "About"), use the menu's name, `wp_get_nav_menu_name( $location )`, so a client renames a heading by renaming the menu in Appearance › Menus. Give `menu()` the same `label`, so the navigation is announced by the heading's name.

**Megamenus** aren't styled in the starter. Matter supports them (a menu item's "Display submenu as a megamenu"); see Matter's README for setting one up, with an example panel and CSS.

## Archives, search and 404

`index.html` (blog, categories, tags, authors, dates), `archive-product.html`, `taxonomy-product_cat.html`, `search.html` and `404.html` each have fixed parts plus Matter's `matter/archive-content` around the list. The fixed parts are breadcrumbs, notices, subcategories and the search form, and they're hidden when empty. Editors add content above and below the list in Appearance › Archives (see Matter's README).

**Breadcrumbs** (`parts/breadcrumbs.html`) are Yoast SEO's: they need Yoast active, with breadcrumbs switched on in its settings, and the band is hidden without them. Outside production Yoast doesn't build its index, so locally a trail can miss parent pages ("Home > Our Story" without "About").

**Products per page** on the shop and category pages are WooCommerce's (columns × rows, 4 × 4 by default; the `loop_shop_per_page` filter): the template's Product Collection takes the main query, so its own `perPage` is ignored. How many go across is the list's variant (`config/matter.php`).

Archives without their own content show a pattern, which is also where editors start when they customise one:
- `patterns/archive.php`: the archive's title and description, then the list;
- `archive-search.php`;
- `archive-404.php`.

Add `pulsar/archive-{post type}` (e.g. `archive-product.php`) to give a post type its own default. The list's variants (Three across, Four across, Feature first) are in `config/matter.php` and styled in `src/css/blocks/matter/archive-loop.scss`.

**Don't add `front-page.html`** for a small difference on the home page (hiding the breadcrumbs, say). WordPress uses it for the front page whether that's a page or the latest posts, so it takes over a blog front page too. Use a rule under `.home` instead.

## Rendering blocks from PHP

```php
Pulsar\the_block( 'woocommerce/mini-cart' );
echo Pulsar\get_block( 'core/search', [ 'label' => 'Search' ] );
```

This works for dynamic blocks only. It runs them through core's render pipeline, so assets and Interactivity API processing work.

## WooCommerce

Shop, product, cart, checkout, order received and account templates are block templates in `templates/`.

- **Product pages:** the gallery stays in view beside the summary; the details (description, additional information, reviews) are an accordion below add to cart, and the rating's "customer reviews" link (`#reviews`) opens the reviews. Related products are a Matter carousel. A variable product's options are chips (`parts/variable-product-add-to-cart-with-options.html`).
- **My Account** has one layout on every page: a header with who's signed in (`template-parts/woocommerce/account-header.php`), and the navigation beside the current section from `lg` (a strip of tabs that scrolls sideways below it). The dashboard is an overview of recent orders, addresses and details (`template-parts/woocommerce/account-dashboard.php`, on WooCommerce's `woocommerce_account_dashboard` hook, so extensions' dashboard content follows it). WooCommerce's own account templates aren't overridden. The header has an account link beside the basket.
- **Order received** is built from WooCommerce's order confirmation blocks (`patterns/order-confirmation.php`).
- **Blocks** are styled per block in `src/css/blocks/woocommerce/`.
- **WooCommerce's classic markup** (My Account's forms, orders, addresses; the customer payment page that payment links open; the classic cart and checkout; extensions' templates) is styled in `src/css/woocommerce.scss`, loaded on every WooCommerce page after WooCommerce's own stylesheet; My Account's layout and navigation in `src/css/woocommerce-account.scss`.
- **Everywhere:** WooCommerce's colour and form variables (`--wc-*`) are mapped to the scheme, and notices use the status colours (`src/css/third-party/_woocommerce.scss`), so WooCommerce's own styles and extensions follow the theme.
- WooCommerce's stylesheets stay on: Pulsar overrides them, matching their specificity where it has to (each case is commented).
