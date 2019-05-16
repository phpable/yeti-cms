<?php

use \Yeti\Blog\Model\Post;
use \Yeti\Core\Model\Page;
use \Yeti\Main\Model\Project;

use \Able\Helpers\Arr;
use \Able\Helpers\Str;
use \Able\Helpers\Src;

use \Able\Reglib\Regex;

use \Yeti\Main\Middleware\Storage;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Request;

/**
 * @return string
 */
function previous(): string {
	return Arr::val(array_reverse(array_values(Arr::cast(Session::get(Storage::NAME, [])))), 1, URL::current());
}

/**
 * @param mixed $val
 * @return string
 */
function valof(&$val) {

	if (is_null($val)) {
		return null;
	}

	if (is_object($val)) {
		return func_num_args() > 1 ?
			$val->{array_slice(func_get_args(), 1)[0]} : null;
	}

	if (is_array($val)) {
		return func_num_args() > 1 && isset($val[$key = array_slice(func_get_args(), 1)[0]])
			? $val[$key] : null;
	}

	return $val;
}

/**
 * @param string $name
 * @param array $Args
 * @return string
 */
function pagelink($name, array $Args = []) {
	return !is_null($Page = Page::where('name', '=', $name)->first())
		? $Page->route($Args) : null;
}

/**
 * @return array
 */
function entrances(): array {
	return [
		'one' => 'One',
		'set' => 'Set',
		'page' => 'Page',
		'list' =>  'List',
	];
}

/**
 * @return array
 */
function share(): array {
	return (Arr::combine($types = array_keys(App::exporter()->getExportableItems()),
		array_map(function(string $value){
			return sprintf('[%s] %s %s', ...array_values(array_map(function(string $name){
				return Src::tcm($name);
			}, Regex::create('/^([^@]+)@([^:]+):(.+)$/')->parse($value, 'vendor', 'module', 'entity'))));

	}, $types)));
}

/**
 * @todo Move this code under the module control.
 * @return array
 */
function groups(): array {
	return [];
//	_dumpe(Post::get()->pluck('markers'));
}

/**
 * @param string $source
 * @return array
 * @throws Exception
 */
function parseJsonNotation(string $source): array {
	if (!preg_match('/^\{(.*)\}$/', $source, $Matches)){
		throw new \Exception('Invalid notation format!');
	}

	$Data = [];
	foreach (preg_split('/\s*,\s*/', $Matches[1], -1 , PREG_SPLIT_NO_EMPTY) as $pair){
		if (!preg_match('/^[\'"]?(' . Regex::RE_VARIABLE . ')[\'"]?\s*:(.*)$/', $pair, $Matches)){
			throw new \Exception('Invalid notation pair format!');
		}

		$Data[$Matches[1]] = $Matches[2];
	}

	return $Data;
}
