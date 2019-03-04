<?php
use \Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Config;
use \Illuminate\Support\Facades\Session;

use \Illuminate\Routing\Router;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Layout;
use \Yeti\Core\Model\Snippet;
use \Yeti\Core\Model\Constant;

use \Yeti\Main\Controller\AuthController;

use \Yeti\Main\Model\User;
use \Yeti\Main\Model\Module;
use \Yeti\Main\Model\Project;

use \Yeti\Main\Building\Builder;

use \MatthiasMullie\Minify\CSS;
use \MatthiasMullie\Minify\JS;

use \Able\Helpers\Url;
use \Able\Helpers\Arr;
use \Able\Helpers\Jsn;
use \Able\Helpers\Src;

use \Able\IO\Path;
use \Able\IO\Directory;

Route::group(['domain' => Config::get('app.domain')], function(){
	Route::group(['middleware' => 'guest'], function () {
		Route::controllers([
			'auth' => 'AuthController',
		]);
	});

	Route::group(['middleware' => 'auth'], function () {
		Route::group(['middleware' => 'unscope'], function () {

			Route::get('/', ['as' => 'yeti@main:dashboard', function() {
				return View::make('dashboard')
					->with('Projects', Project::all());
			}]);

			Route::post('/', function(){
				Session::put('__SCOPE__', Project::findOrFail(Input::get('project'))->id);
				return redirect(route('yeti@core:pages.all'));
			});

			Route::get('/configuration', ['as' => 'yeti@main:configuration', function() {
				return View::make('configuration.edit')
					->with('Modules', Module::all());
			}]);
		});

		Route::group(['middleware' => 'scope'], function () {
			Route::get('/files', ['as' => 'yeti@main:files',
				'uses' => 'FilesController@manager']);

			Route::group(['middleware' => 'ajax'], function () {
				Route::get('/deploy', ['as' => 'yeti@main:deploy',
					'uses' => 'DeployController@deploy']);
				Route::get('/deploy/check', ['as' => 'yeti@main:deploy.check',
					'uses' => 'DeployController@check']);
				Route::post('/editor/rename', ['as' => 'yeti@main:editor.rename',
					'uses' => 'EditorController@rename']);
				Route::post('/editor/create', ['as' => 'yeti@main:editor.create',
					'uses' => 'EditorController@create']);
				Route::post('/editor/delete', ['as' => 'yeti@main:editor.delete',
					'uses' => 'EditorController@delete']);
				Route::get('/files/load', ['as' => 'yeti@main:files.load',
					'uses' => 'FilesController@load']);
				Route::post('/files/upload', ['as' => 'yeti@main:files.upload',
					'uses' => 'FilesController@upload']);
				Route::post('/files/create-folder', ['as' => 'yeti@main:files.create-folder',
					'uses' => 'FilesController@createFolder']);
			});

			Route::get('/{type}/{name}', ['as' => 'yeti@main:resources',
				'uses' => 'ResourcesController@proxyResource']);
		});
	});

	if (file_exists($file = temporary_path('routes.php'))){
		include_once($file);
	}
});

/**
 * @param string $mask
 * @param string $url
 * @param array $Params
 * @throws \Exception
 * @return bool
 */
function checkUrlByMask(string $mask, string $url, array &$Params = []): bool {
	foreach (preg_split('/\/+/', $mask, -1, PREG_SPLIT_NO_EMPTY) as $mask){
		if (preg_match('/^\{\$([A-Za-z0-9-_]+):?([0-9A-Za-z_-]*)\}$/', $mask, $Fragments) > 0) {
			$Params[$Fragments[1]] = $Fragments[2];

			if (preg_match('/^([^\/]+)(\/|\Z)/', $url, $Matches) > 0) {
				$Params[$Fragments[1]] = $Matches[1];
				$url = $Matches[2];
				continue;
			}

			if (!empty($Params[$Fragments[1]])){
				continue;
			}
		}

		if (preg_match('/^' . preg_quote($mask, '/') . '(\/.*$|\Z)/', $url, $Matches)){
			$url = ltrim($Matches[1], '/');
			continue;
		}

		return false;
	}

	return strlen($url) < 1;
}

/**
 * @param string $url
 * @param array $Params
 * @return Page|null
 * @throws Exception
 */
function findPageByUrl(string $url, array &$Params = []): ?Page {
	if (!is_null($Page = Page::where('url', '=', '/' . ltrim($url, '/'))->first())) {
		return $Page;
	}

	foreach (Page::where('url', 'like', '%$%')->get() as $Page){
		if (checkUrlByMask($Page->url, $url, $Params)){
			return $Page;
		}
	}

	throw new \Exception('Invalid page!');
}

Route::group(['domain' => '{project}.' . Config::get('app.domain')], function() {
	Route::get('/{url}', function($project, $url){
		try {
			if (is_null($Scope = Project::where('name', '=', $project)->first())) {
				throw new \Exception('Invalid scope!');
			}

			define('__SCOPE__', $Scope->id);

			$Params = [];
			$Page = findPageByUrl($url, $Params);

			$Directory = Path::create(Config::get('building.destination'))
				->append($project, 'pages')->forceDirectory();

			if (!env('APP_DEBUG_TEMPLATES') || $Directory->isEmpty()){
				(new Builder($Page))->build($Directory);
			}

			$Root = $Directory->toPath()
				->append($Page->name)->toDirectory();

			$View = $Root->toPath()->append('view.php')
				->toFile();

			$Page = (object)Jsn::decode($Root->toPath()
				->append('page.json')->toFile()->getContent());

			$Temporary = Path::create(Config::get('building.destination'))
				->append($project, 'resources', 'temporary')->toDirectory();

			$CssSource = Path::create(Config::get('building.destination'))
				->append($project, 'resources', 'styles')->toDirectory();

			$JsSource = Path::create(Config::get('building.destination'))
				->append($project, 'resources', 'scripts')->toDirectory();

			$Temporary->clear();
			minimizeJs($JsSource, $Temporary);
			minimizeCss($CssSource, $Temporary);

			ob_start();
			call_user_func(function() use ($Page, $View, $Params){
				$__data = array_merge(Arr::except(get_defined_vars(), 'View', 'Params'),
					$Params, ['__public' => '/temporary']);

				include($View->toString());
			});

			echo ob_get_clean();

		}catch (\Throwable $Exception){
			while (ob_get_level() > 0){
				ob_get_clean();
			}

			Log::error($Exception);
			abort(404, $Exception->getMessage());
		}

	})->where('url', '^.*$');
});


/**
 * @param Directory $Source
 * @param Directory $Destination
 * @throws \Exception
 */
function minimizeCss(Directory $Source, Directory $Destination): void {
	$Minimifier = new CSS();

	foreach ($Source->toPath()->append('.minify')->forceFile()->toReader()->read() as $file) {
		$Minimifier->add($Source->toPath()->append($file)->toFile()->toString());
	}

	$Minimifier->minify($Destination->toPath()
		->forceDirectory()->toPath()->append('main.min.css')->forceFile()->toString());
}

/**
 * @param Directory $Source
 * @param Directory $Destination
 * @throws \Exception
 */
function minimizeJs(Directory $Source, Directory $Destination): void {
	$Minimifier = new JS();

	foreach ($Source->toPath()->append('.minify')->forceFile()->toReader()->read() as $file) {
		$Minimifier->add($Source->toPath()->append($file)->toFile()->toString());
	}

	$Minimifier->minify($Destination->toPath()
		->forceDirectory()->toPath()->append('main.min.js')->forceFile()->toString());
}
