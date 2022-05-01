const config = require('./config/mix.json')
const mix = require('laravel-mix')
const fs = require('fs')

require('laravel-mix-clean')

/*
 * Separate dev/live destinations to prevent dev files from ever being uploaded, and
 * to help reduce unnecessary code conflicts when working alongside other developers.
 */

if (mix.inProduction()) {
    mix.setPublicPath('dist/mix/live')
    mix.version()
} else {
    mix.setPublicPath('dist/mix/dev')
}

/*
 * Stylesheets
 */

config.entries.css.forEach(
    entry => {
        mix.postCss(entry, 'css')
    }
)

mix.options(
    {
        processCssUrls: false,
        postCss: [
            require('autoprefixer'),
            require('postcss-import'),
            require('postcss-nested'),
            require('tailwindcss')
        ]
    }
)

/*
 * JavaScript
 */

config.entries.js.forEach(
    entry => {
        mix.js(entry, 'js')
    }
)

mix.autoload(
    {
        jquery: ['$', 'window.jQuery']
    }
)

mix.extract()

/*
 * Browsersync (only if config/browsersync.json exists)
 */

if (fs.existsSync('./config/browsersync.json')) {
    const browserSyncConfig = require('./config/browsersync.json')
    browserSyncConfig.files = [
        'dist/mix/dev/js/**/*.js',
        'dist/mix/dev/css/**/*.css',
        'parts/**/*.php',
        'pattermns/**/*.php',
        'templates/**/*.php',
    ]
    mix.browserSync(browserSyncConfig)
}

/*
 * Housekeeping
 */

mix.clean()
