<?php
namespace Yeti\Main\Providers;

use \Illuminate\Http\Request;
use \Illuminate\Routing\Router;
use \Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Route;

use \Able\Helpers\Arr;
use \Able\Helpers\Src;

class YetiRouteProvider extends RouteServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 * In addition, it is set as the URL generator's root namespace.
	 * @var string
	 */
	protected $namespace = '\Yeti\Main\Controller';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 * @param  \Illuminate\Routing\Router $Router
	 * @return void
	 */
	public function boot(Router $Router) {
		parent::boot($Router);
	}

	/**
	 * Define the routes for the application.
	 * @param  \Illuminate\Routing\Router $Router
	 * @return void
	 */
	public function map(Router $Router) {
		$Router->group(['namespace' => $this->namespace], function ($Router) {
			require base_path('bootstrap/routes.php');

			$Items = array_filter(array_map(function($item){
				return preg_replace('/\.php$/', null,
					preg_replace('/^.*\//', null, $item));
			}, glob(app_path() . '/Controller/*Controller.php')), function($item){
				return $item !== 'AuthController';
			});

			foreach($Items as $name){
				$class = $this->namespace . '\\' . $name;
				if (!class_exists($class)) {
					throw new \Exception('Unloadable controller!');
				}

				$prefix = Src::fcm(preg_replace('/Controller$/',
					null, $name));

				$Methods = array_diff(get_class_methods($class),
					get_class_methods(get_parent_class($class)));

				foreach ($Methods as $method){

					$pattern = '/main/' . $prefix . '/' . $method;

					$Params = (new \ReflectionClass($class))->getMethod($method)
						->getParameters();

					foreach($Params as $Param) {
						$pattern .= '/{' . $Param->getName() . '}';
					};

					Route::group(['middleware' => ['auth','scope']], function () use ($pattern, $method, $name, $prefix){
						Route::any($pattern, ['as' => 'main.'
							. $prefix . '.' . $method, 'uses' => $name . '@' . $method])->where('params', '.*');
					});

				}
			}

		});
	}
}
