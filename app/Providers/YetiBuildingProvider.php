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

use \Able\Reglib\Reglib;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Layout;
use \Yeti\Core\Model\Snippet;
use \Yeti\Core\Model\Constant;
use \Yeti\Core\Model\Template;

use \Yeti\Main\Building\Utilities\Collector;

use \Able\Helpers\Str;

use function \Able\Sabre\Standard\checkArraySyntax;
use \Yeti\Main\Building\Builders\Abstractions\ABuilder;

use \Illuminate\Support\ServiceProvider;

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

		Delegate::token(new SToken('export', function ($name, $scope, $condition) {
			if (!preg_match('/^\$' . Reglib::VAR . '$/', $name)){
				throw new \Exception(sprintf('Invalid variable name: %s!', $name));
			}

			$name = substr($name, 1);
			$takeOnlyCount = false;

			if ($scope == 'url'){
				$condition = 'md5(' . $condition . ')';
			}elseif ($scope == 'id') {
				$condition = '"' . $condition . '"';
			}elseif ($scope == 'name'){
				$condition = 'md5(' . $condition . ')';
			}elseif ($scope == '@pagination') {
				$condition = '"page" . ' . $condition;
			}elseif ($scope == '#topic') {
				$condition = '"topic" . md5(' . $condition . ')';
			}elseif ($scope == '#author' || $scope == '#author-latest') {
				$condition = '"author" . md5(' . $condition . ')';
			}elseif ($scope == "@count"){
				$takeOnlyCount = true;
			}else{
				throw new \Exception(sprintf('Undefined scope: %s!', $scope));
			}

			if ($takeOnlyCount){
				return '<?php  if (is_dir($dir = __DIR__ . "/data/")){
					extract(["' . $name . '" => count(glob($dir . "*/*.data"))]);
				}?>';
			}

			return '<?php  if (file_exists($file = __DIR__ . "/data/' . $name . '/" . ' . $condition . ' . ".data")){
				extract(["' . $name . '" => json_decode(base64_decode(file_get_contents($file)))]);
				if(isset($' . $name . '->meta_title)){
					$Page->title = $' . $name . '->meta_title;
				};
				if(isset($' . $name . '->meta_description)){
					$Page->description = $' . $name . '->meta_description;
				};
			}?>';
		}, 3, false));
	}
}
