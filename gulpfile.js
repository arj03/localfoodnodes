process.env.DISABLE_NOTIFIER = true;
const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.sass([
        'public.sass',
        'admin.sass'
    ]);

    mix.version([
        'css/public.css',
        'css/admin.css',
        './public/js-apps/node-map.js',
        './public/js-apps/producer-node-map.js',
    ]);
});
