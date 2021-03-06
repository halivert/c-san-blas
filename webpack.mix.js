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

mix.js('resources/js/welcome.js', 'public/js');
mix.js('resources/js/marked-houses.js', 'public/js');
mix.js('resources/js/exhibitors.js', 'public/js');

mix.sass('resources/sass/safe-ocean.scss', 'public/css');

mix.extract();
mix.version();
