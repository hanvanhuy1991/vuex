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

mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            'scss@': path.resolve('resources/assets/admin/sass'),
            '@': path.resolve('resources/assets/admin/js'),
        },
    },
});

mix.sass('resources/assets/admin/sass/app.scss', 'public/admin/css');
mix.copyDirectory('resources/assets/admin/image', 'public/admin/image');

mix.js('resources/assets/admin/js/app.js', 'public/admin/js')
    .copyDirectory('node_modules/admin-lte/dist/', 'public/dist/')
    .copyDirectory('node_modules/admin-lte/plugins/', 'public/plugins/');

if (mix.inProduction()) {
    mix.copyDirectory([
        'node_modules/swagger-ui-dist/swagger-ui-bundle.js',
        'node_modules/swagger-ui-dist/swagger-ui.css',
        'node_modules/swagger-ui-dist/favicon-32x32.png',
        'node_modules/swagger-ui-dist/favicon-16x16.png',
        'node_modules/swagger-ui-dist/swagger-ui-standalone-preset.js',
        'node_modules/swagger-ui-dist/swagger-ui-standalone-preset.js.map',
        'node_modules/swagger-ui-dist/swagger-ui.css.map',
        'node_modules/swagger-ui-dist/swagger-ui-bundle.js.map',
    ], 'public/swagger');
}

mix.version();
mix.disableNotifications();