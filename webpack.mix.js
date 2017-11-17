let mix = require('laravel-mix');
mix.disableSuccessNotifications();

mix.sass('resources/assets/sass/public.sass', 'public/css').version();
mix.sass('resources/assets/sass/account.sass', 'public/css').version();
mix.sass('resources/assets/sass/admin.sass', 'public/css').version();

mix.js('resources/assets/js/vue/admin/admin.js', 'public/js').version();
mix.js('resources/assets/js/vue/public/frontpage.js', 'public/js').version();
mix.js('resources/assets/js/vue/public/transactions.js', 'public/js').version();
mix.js('resources/assets/js/react/dist/node-map.js', 'public/js').version();
mix.js('resources/assets/js/react/dist/producer-node-map.js', 'public/js').version();
