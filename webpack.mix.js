const mix = require('laravel-mix');
const webpack = require('webpack');

mix
  // js
  .js('resources/js/aos.js', 'public/js')
  .js('resources/js/preloader.js', 'public/js')
  // less
  .less('resources/css/index.less', 'public/css')
  .less('resources/css/media.less', 'public/css')
  .less('resources/css/memorial.less', 'public/css')
  // css
  .css('resources/css/aos.css', 'public/css')
  .css('resources/css/normalize.css', 'public/css')
;

if (mix.inProduction()) {
  mix.version();
} else {
  mix.sourceMaps();
}
