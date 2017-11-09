let mix = require('laravel-mix');
mix.disableSuccessNotifications();

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

mix.sass('resources/assets/sass/public.sass', 'public/css').version();
mix.sass('resources/assets/sass/account.sass', 'public/css').version();
mix.sass('resources/assets/sass/admin.sass', 'public/css').version();
mix.js('resources/assets/js/vue/admin.js', 'public/js').version();
mix.js('resources/assets/js/react/dist/node-map.js', 'public/js').version();
mix.js('resources/assets/js/react/dist/producer-node-map.js', 'public/js').version();
