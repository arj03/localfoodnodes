let mix = require('laravel-mix');
mix.disableSuccessNotifications();

// Styles
mix.sass('resources/assets/sass/public.sass', 'public/css').version();
mix.sass('resources/assets/sass/account.sass', 'public/css').version();
mix.sass('resources/assets/sass/admin.sass', 'public/css').version();

// Vue
mix.js('resources/assets/js/vue/admin/admin.js', 'public/js').version();
mix.js('resources/assets/js/vue/public/economy-in-out/economy-in-out.js', 'public/js').version();
mix.js('resources/assets/js/vue/public/economy-circulation/economy-circulation.js', 'public/js').version();
mix.js('resources/assets/js/vue/public/transactions/transactions.js', 'public/js').version();
mix.js('resources/assets/js/vue/public/statistics/statistics.js', 'public/js').version();

// React
mix.js('resources/assets/js/react/dist/node-map.js', 'public/js').version();
mix.js('resources/assets/js/react/dist/producer-node-map.js', 'public/js').version();
