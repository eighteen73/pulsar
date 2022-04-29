const mix = require('laravel-mix');
const config = require('./config/mix.json');

// Plugins.
require('laravel-mix-clean');
require('laravel-mix-copy-watched');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Sage application. By default, we are compiling the Sass file
 | for your application, as well as bundling up your JS files.
 |
 */

mix
  .setPublicPath('./dist')
  .browserSync(
    config.browserSync.settings,
		config.browserSync.options
  );

config.entries.css.forEach(entry => {
  mix.postCss(entry, 'css')
});

config.entries.js.forEach(entry => {
  mix.js(entry, 'js')
});

mix
  .copyDirectoryWatched('src/fonts', 'dist/fonts')
  .copyDirectoryWatched('src/img', 'dist/img')
  .copyDirectoryWatched('src/svg', 'dist/svg');

mix
  .autoload({
	jquery: [
	  '$',
	  'window.jQuery'
	]
  })
  .options({
    processCssUrls: false,
  })
  .version()
  .clean();

if (!mix.inProduction()) {
  mix.sourceMaps();
}
