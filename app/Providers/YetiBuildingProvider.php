<?php
namespace Yeti\Main\Providers;

use \Able\Sabre\Compiler;
use \Able\Sabre\Utilities\Queue;

use \Able\Sabre\Structures\SToken;
use \Able\Sabre\Structures\STrap;

use \Able\Sabre\Standard\Delegate;

use \Able\IO\Path;
use \Able\IO\ReadingBuffer;
use \Able\IO\WritingBuffer;

use \Able\Reglib\Regex;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Config;
use \Illuminate\Support\ServiceProvider;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Layout;
use \Yeti\Core\Model\Snippet;
use \Yeti\Core\Model\Constant;
use \Yeti\Core\Model\Template;

use \Yeti\Main\Building\Utilities\Collector;

use \Able\Helpers\Str;

use function \Able\Sabre\Standard\checkArraySyntax;


class YetiBuildingProvider extends ServiceProvider {

	/**
	 * Register any application services.
	 * @throws \Exception
	 */
	public function register() {
		Delegate::registerSourcePath(Path::create(base_path(),
			'bootstrap'), 'base');

		Delegate::token(new SToken('build', function (string $name, Queue $Queue, Compiler $Compiler) {
			switch (strtolower($name)){
				case 'constants':
					return '<?php extract(' . Str::rmnl(var_export(Constant::all()->pluck('value', 'name')->toArray(), true)) . ');?>';
				case 'metadata':
					return Collector::metas()->toHtml();
				case 'externals':
					return Collector::externals()->toHtml();
				default:
					throw new \Exception(sprintf('Undefined building targed: %s!', $name));
			}
		}, 1, false, true));

		Delegate::token(new SToken('e64', function (string $value) {
			return '<?php extract(' . Str::rmnl(var_export(json_decode(base64_decode($value), true), true)) . '); ?>';
		}, 1, false));

		Delegate::token(new SToken('page', function (string $name, Queue $Queue, Compiler $Compiler) {
			$Queue->immediately(Collector::restack('page')->combine($name));
		}, 1, false, true));

		Delegate::token(new SToken('snippet', function (string $name, ?string $params, Queue $Queue, Compiler $Compiler) {
			if (is_null($Snippet = Snippet::where('name', '=', $name)->first())){
				throw new \Exception('Undefined snippet: ' . $name . '!');
			}

			if (!empty($params)){
				try {
					$params = parseJsonNotation($params);
				}catch (\Throwable $Exception){
					throw new \Exception('Invalid parameters!', -1, $Exception);
				}
			}else{
				$params = [];
			}

			$Queue->immediately((new Collector($Snippet))->combine('main', $params));
		}, 2, false, true));

		Delegate::token(new SToken('component', function ($name, $params) {
			if (!is_null($params) && !checkArraySyntax($params)){
				throw new \Exception('Invalid parameters!');
			}

			return '<?php  if (function_exists("init_component")){init_component("' . $name
 				. '", array_merge($__obj->f(get_defined_vars()), ' . (!is_null($params) ? $params : '[]') . '));}?>';
		}, 2, false));

		/** @noinspection PhpUnhandledExceptionInspection */
		Delegate::token(new SToken('load', function ($name, $scope, $condition) {
			if (!preg_match('/^\$' . Regex::RE_VARIABLE . '$/', $name)){
				throw new \Exception(sprintf('Invalid variable name: %s!', $name));
			}

			return '<?php  if (file_exists($file = __DIR__ . "/../../data/' . strtolower($scope) . '/data.php")) {
				
				extract(["' . substr($name, 1) . '" => call_user_func(function($file, $key){ return include($file); }, $file, ' . (!empty($condition) ? $condition : 'null'). ')]);
				
				if(!empty(' . $name . '->meta_title)){
					$Page->title = ' . $name . '->meta_title;
				}elseif(!empty(' . $name . '->title)){
					$Page->title = ' . $name . '->title;
				};

				if(!empty(' . $name . '->meta_description)){
					$Page->description = ' . $name . '->meta_description;
				};
			}?>';
		}, 3, false));

		/** @noinspection PhpUnhandledExceptionInspection */
		Delegate::token(new SToken('count', function ($name, $scope) {
			if (!preg_match('/^\$' . Regex::RE_VARIABLE . '$/', $name)){
				throw new \Exception(sprintf('Invalid variable name: %s!', $name));
			}

			return '<?php  if (is_dir($dir = __DIR__ . "/../../data/' . strtolower($scope) . '/")) {
				extract(["' . substr($name, 1) . '" => count(array_filter(scandir($dir), function($value){
					return preg_match("/\\\\.data$/", $value);
				}))]);
			}?>';
		}, 3, false));

		/** @noinspection PhpUnhandledExceptionInspection */
		Delegate::token(new SToken('auth', function () {
			return '<?php if (Auth::check()){?>';
		}));

		Delegate::finalize('auth', new SToken('off', function(){
			return '<?php } ?>';
		}));

		/** @noinspection PhpUnhandledExceptionInspection */
		Delegate::token(new SToken('guest', function () {
			return '<?php if (!Auth::check()){?>';
		}));

		Delegate::finalize('guest', new SToken('off', function(){
			return '<?php } ?>';
		}));
	}
}
