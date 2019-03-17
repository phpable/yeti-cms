const mix = require('laravel-mix');
const clean = require('clean-webpack-plugin');

const temporary = __dirname + '/public/temporary/';
const node = __dirname + '/node_modules/';
const resources = __dirname + '/resources/';

const paths = {
	bootstrap: node + 'bootstrap-sass/assets/',
	awesome: node + 'font-awesome/',
	base64: node + 'base-64/',
	underscore: node + 'underscore/',
	piechart: node + 'easy-pie-chart/dist/',
	cookie: node + 'jquery.cookie/',
	jselect: node + 'jquery-editable-select/',
	jtags: node + 'suggestags/',
	fileupload: node + 'blueimp-file-upload/',
	ace: node + 'ace-builds/src/',
	assets: resources + 'assets/',
	plugins: resources + 'plugins/',
	fonts: resources + 'fonts/',
	js: resources + 'js/'
};

mix.webpackConfig({
	plugins: [
		new clean([temporary + '/*', 'public/css/*', 'public/js/*',
			'public/images/*', 'public/fonts/*'], {verbose: false})
	],
});

mix.copy('resources/images/**', 'public/images');

mix.copy(paths.ace + 'ace.js', temporary + 'js/editor/');
mix.copy(paths.ace + 'theme-solarized_light.js', temporary + 'js/editor/');
mix.copy(paths.ace + 'ext-language_tools.js', temporary + 'js/editor/');

mix.copy(paths.ace + 'mode-html.js', temporary + 'js/editor/');
mix.copy(paths.ace + 'mode-css.js', temporary + 'js/editor/');
mix.copy(paths.ace + 'mode-javascript.js', temporary + 'js/editor/');

mix.copy(paths.ace + 'worker-html.js', temporary + 'js/editor/');

mix.copy(paths.ace + 'worker-javascript.js', 'public/js/editor/');
mix.copy(paths.ace + 'worker-css.js', 'public/js/editor/');

mix.copy(paths.ace + 'snippets/text.js', temporary + 'js/editor/');
mix.copy(paths.ace + 'snippets/html.js', temporary + 'js/editor/');
mix.copy(paths.ace + 'snippets/css.js', temporary + 'js/editor/');
mix.copy(paths.ace + 'snippets/javascript.js', temporary + 'js/editor/');

mix.copy(paths.piechart + 'jquery.easypiechart.js', temporary + 'js/');
mix.copy(paths.cookie + 'jquery.cookie.js', temporary + 'js/');
mix.copy(paths.jselect + 'src/jquery-editable-select.js', temporary + 'js/');
mix.copy(paths.jtags + 'js/jquery.amsify.suggestags.js', temporary + 'js/');

mix.copy(paths.fileupload + 'js/jquery.fileupload.js', temporary + 'js/');
mix.copy(paths.fileupload + 'js/vendor/jquery.ui.widget.js', temporary + 'js/');
mix.copy(paths.fileupload + 'css/jquery.fileupload.css', temporary + 'css/');
mix.copy(paths.plugins + 'summernote/fonts/*', 'public/fonts/');

mix.sass(paths.assets + '/sass/bootstrap.sass', temporary + 'css/bootstrap.sass.css', {
	includePaths: [
		paths.bootstrap + 'stylesheets/',
		paths.awesome + 'scss/',
	],
});

mix.styles([
	paths.assets + 'css/app.theme.css',
	paths.assets + 'css/jquery.scrollbar.css',
	paths.assets + 'css/jquery.filemanager.css',
	paths.jselect + 'src/jquery-editable-select.css',
	paths.jtags + 'css/amsify.suggestags.css',
],temporary + 'css/app.static.css');

mix.sass(paths.assets + '/sass/main.sass',
	temporary + 'css/main.sass.css');

mix.styles([
	temporary + 'css/bootstrap.sass.css',
	temporary + 'css/app.static.css',
	temporary + 'css/jquery.fileupload.css',
	temporary + 'css/main.sass.css',
], 'public/css/main.css');

mix.styles([
	paths.plugins + 'summernote/css/summernote.css',
], 'public/css/summernote.css');

mix.scripts(paths.assets + 'js/*',
	temporary + 'js/main.js');

mix.scripts([paths.js + 'jquery.js'],
	temporary + 'js/jquery.js');

mix.scripts([paths.base64 + 'base64.js'],
	temporary + 'js/base64.js');

mix.scripts([paths.underscore + 'underscore.js'],
	temporary + 'js/underscore.js');

mix.scripts([paths.bootstrap + 'javascripts/bootstrap.js'],
	temporary + 'js/bootstrap.js');

mix.scripts([
	temporary + 'js/editor/ace.js',
	temporary + 'js/editor/theme-solarized_light.js',
	temporary + 'js/editor/mode-html.js',
	temporary + 'js/editor/mode-css.js',
	temporary + 'js/editor/mode-javascript.js',
	temporary + 'js/editor/ext-language_tools.js',
	temporary + 'js/editor/worker-html.js.js',
	temporary + 'js/editor/text.js',
	temporary + 'js/editor/html.js',
	temporary + 'js/editor/css.js',
	temporary + 'js/editor/javascript.js',
],'public/js/editor.js');

mix.scripts([
	paths.plugins + 'summernote/js/summernote.js',
	paths.plugins + 'summernote/js/summernote-image-title.js',
	paths.plugins + 'summernote/js/summernote-image-caption.js',
	paths.plugins + 'summernote/js/summernote-image-captionit.js',
], 'public/js/summernote.js');

mix.scripts([
	temporary + 'js/base64.js',
	temporary + 'js/underscore.js',
	temporary + 'js/jquery.js',
	temporary + 'js/bootstrap.js',
	temporary + 'js/jquery.cookie.js',
	temporary + 'js/jquery.easypiechart.js',
	temporary + 'js/jquery-editable-select.js',
	temporary + 'js/jquery.amsify.suggestags.js',
	temporary + 'js/jquery.ui.widget.js',
	temporary + 'js/jquery.fileupload.js',
	temporary + 'js/main.js',
],'public/js/main.js');
