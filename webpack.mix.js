let mix = require('laravel-mix');

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

mix.options({
    uglify: {
        uglifyOptions: {
            compress: {
                drop_console: true
            },
            ie8: true ,
            safari10: true
          }
    }
});

mix.setResourceRoot('/qrlab/public/');

mix.react('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/assets/sass/login.scss', 'public/css');

//mix.babel('public/js/app.js', 'public/js/app.js');

mix.browserSync('mis_test.metrosystems.co.th/qrbox');
