const mix = require("laravel-mix");

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
mix.disableSuccessNotifications();
mix.js("resources/assets/js/app.js", "public/js").sass("resources/assets/sass/app.scss", "public/css");
mix.js("resources/assets/js/auth.js", "public/js");

mix.version();

mix.browserSync(process.env.BROWSER_SYNC_ADDR);

mix.webpackConfig((webpack) => {
  return {
    plugins: [
      new webpack.DefinePlugin({
        __NODE_ENV: JSON.stringify(process.env.NODE_ENV),
      }),
      new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
    ],
  };
});
