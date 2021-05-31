const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath('../');
mix.setResourceRoot('../');

mix.js('resources/js/app.js', '../js')
    .sass('resources/sass/app.scss', '../css')
    .postCss('resources/css/app.css', '../css')
    .sourceMaps();

mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', '../webfonts');
mix.copyDirectory('resources/fonts', '../fonts');
mix.copyDirectory('resources/images', '../images');
mix.copyDirectory('public/vendor', '../vendor');
mix.copyDirectory('node_modules/mdbootstrap/img', '../img');
