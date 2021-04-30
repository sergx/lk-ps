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

// mix
//   .js("resources/js/app.js", "public/js")
//   .vue({ version: 2 })
//   .sass("resources/sass/app.scss", "public/css")
//   .sourceMaps();
// if (mix.inProduction()) {
//   mix.version();
// }

require("laravel-mix-merge-manifest"); // Добавить строчку
mix.setPublicPath("../docs/lk").mergeManifest(); // Добавить строчку с указанием на паш путь к папке
mix
  .js("resources/js/app.js", "js")
  .vue({ version: 2 }) // поменять public на lk
  .sass("resources/sass/app.scss", "css")
  .sourceMaps();
mix.version();
