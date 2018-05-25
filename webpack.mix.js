let mix = require("laravel-mix");

require("laravel-mix-tailwind");
require("laravel-mix-purgecss");

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

mix.js("resources/assets/js/app.js", "public/js")
    .copy(["resources/assets/js/three.js", "resources/assets/js/three.orbitcontrols.js", "resources/assets/js/three.tgxloader.js"], 'public/js')
   .postCss("resources/assets/css/app.css", "public/css")
   .tailwind()
   .purgeCss();

if (mix.inProduction()) {
  mix.version();
}
