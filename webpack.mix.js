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

mix.js("resources/js/app.js", "public/js")
    .copy(["resources/js/three.js", "resources/js/three.orbitcontrols.js", "resources/js/three.tgxloader.js"], 'public/js')
   .postCss("resources/css/app.css", "public/css")
   .tailwind()
   .purgeCss();

if (mix.inProduction()) {
  mix.version();
}
