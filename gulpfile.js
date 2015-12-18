var elixir = require('laravel-elixir');

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

elixir(function(mix) {
    mix.scriptsIn('resources/assets/js/angular', 'public/js/app.js');

    mix.less('app.less');

    mix.version([
        'public/js/app.js',
        'public/css/app.css'
    ]);
});
