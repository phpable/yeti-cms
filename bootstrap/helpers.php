<?php
use \Yeti\Core\Model\Page;
use \Yeti\Main\Model\Project;

use \Able\Helpers\Arr;
use \Able\Helpers\Str;

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
 * @param null|Page $Page
 * @return string
 */
function preview(?Page $Page = null) {
	return Request::getScheme() . '://' . App::scope()->name . '.' . Request::getHost()
	. (!is_null($Page) && !empty($Page->url) ? ('/' . ltrim($Page->url, '/')) : null);
}

/**
 * @return array
 */
function builders(): array {
	return ['standard' => '[Core] Standard Builder', 'extended' => '[Core] Extended Builder'];
}

/**
 * @return array
 */
function entrances(): array {
	return ['single' => 'One', 'multiple' => 'Many'];
}

/**
 * @return array
 */
function share(): array {
	return ['blog-post' => '[Blog] Post', 'blog-topic' => '[Blog] Topic', 'blog-tag' => '[Blog] Tag'];
}

/**
 * @return array
 */
function properties(): array {
	return [
		'url' => '[native] Url',
		'id' => '[native] Id',
		'#topic' => '[group] Topic',
		'@paginator' => '[generic] Paginator',
	];
}
