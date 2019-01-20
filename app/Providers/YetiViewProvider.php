<?php
namespace Yeti\Main\Providers;

use \Illuminate\Support\Facades\View;
use \Illuminate\Support\Facades\Blade;

use \Yeti\Core\Model\Page;
use \Yeti\Main\Model\Project;

use \Able\Reglib\Reglib;

use \Able\LaravelBridge\BridgeViewServiceProvider;

class YetiViewProvider extends BridgeViewServiceProvider {

	/**
	 * Bootstrap any application services.
	 */
	public function boot() {
		include_once(base_path('bootstrap/helpers.php'));

		if (file_exists($file = temporary_path('resources.php'))){
			foreach (include($file) as $namespace => $path){
				View::addNamespace($namespace, $path);
			}
		}

		Blade::directive('param', function($string){
			$Params = preg_split('/[,\s]+/', substr($string, 1,
				strlen($string) - 2), -1, PREG_SPLIT_NO_EMPTY);

			return "<?php\nif (!isset(" . ($name = array_shift($Params)) . ")) {\n\t"
				. $name . ' = ' . (count($Params) > 0 ? array_shift($Params) : 'null') . ";\n}\n" . '?>';
		});

		Blade::directive('object', function($string){
			$Params = preg_split('/[,\s]+/', trim($string, ')('), -1, PREG_SPLIT_NO_EMPTY);
			return "<?php\nif (!isset(" . ($name = array_shift($Params)) . ")) {\n\t" . $name . " = [];\n}\n"
				. 'if (!is_object(' . $name . ")) {\n\t" . $name . ' = (object)' . $name . ";\n}\n"
					. 'foreach ([' . implode(',', $Params) . '] as $key) {' . "\n\t" . 'if (!isset('
						. $name . '->{$key})) {' . "\n\t\t" . $name . '->{$key} = null;' . "\n\t}\n}\n?>";
		});
	}

	/**
	 * Register any application services.
	 */
	public function register() {
		parent::register();

		View::composer('workspace', function($View) {
			$View->with('__HAS_BUGS__', file_exists(base_path('storage')
				. '/yeti.error.log'));
		});
	}

}
