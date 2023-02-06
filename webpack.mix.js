const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('framework/resources/js/app.js', 'public/js')
//     .postCss('framework/resources/css/app.css', 'public/css', [
//         //
//     ]);

mix
    .js('framework/resources/assets/site/js/app.js', './js')
    .js('framework/resources/assets/backend/js/app.js', './backend/js')
    .sass('framework/resources/assets/site/sass/app.scss', './css')
    .sass('framework/resources/assets/backend/sass/app.scss', './backend/css');
