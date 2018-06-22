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

mix.scripts([
	'resources/assets/js/vue.js',	
	'resources/assets/js/axios.js',	
	'resources/assets/js/jquery-3.3.1.min.js.js',
	'resources/assets/js/toastr.js',
	'resources/assets/js/app.js',
	//'resources/assets/js/chartist.min.js',
	'resources/assets/js/pace.min.js',			
	'resources/assets/js/sync.js',	
	],'public/js/sync.js')   
	.styles([	
	'resources/assets/css/indice.css',	
	//'resources/assets/css/chartist.min.css',
	],'public/css/sync.css'); 