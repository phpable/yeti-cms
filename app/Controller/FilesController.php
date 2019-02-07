<?php
namespace Yeti\Main\Controller;

use \Illuminate\Support\Facades\Bus;
use \Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;

use \Illuminate\View\View;

use \Yeti\Main\Controller\Abstracts\AController;
use \Yeti\Main\Macros\ProcessMedia;

use \Able\Helpers\Arr;
use \Able\Helpers\Str;

use \Able\IO\Path;
use \Able\IO\Directory;

class FilesController extends AController {

	/**
	 * @const string
	 */
	public const RT_MEDIA = 'media';

	/**
	 * @const string
	 */
	public const RT_BLOG = 'blog';

	/**
	 * @const string
	 */
	public const RT_AUTHORS = 'author';

	/**
	 * @param string $type
	 * @return Path
	 */
	private function getResourceRoot(string $type = 'media'): Path {
		return $this->RootCache = App::scope()->path->append('resources', $type)->try(function () {
			throw new \Exception('Resource root is not exist or not writable!');
		}, Path::TIF_NOT_DIRECTORY | Path::TIF_NOT_WRITABLE);
	}

	/**
	 * @return View
	 */
	public function manager(): View {
		return view('files.manager');
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function load() {
		$Directory = $this->getResourceRoot()
			->append(Input::get('path'))->toDirectory();

		if (!$Directory->toPath()->isChildOf($this->getResourceRoot())){
			throw new \Exception('Access denied!');
		}

		$Items = [];
		foreach($Directory->list() as $Path){
			if (!$Path->isDot() && !$Path->isLink()){
				$Items[$Path->getEnding()] = [
					'name' => $Path->getEnding(),
					'type' => $Path->isDirectory() ? 'folder' : 'file',
					'path' => $Path->toPath()->exclude($this->getResourceRoot())->toString(),
					'size' => $Path->toNode()->getSize(),
				];
			}
		}

		ksort($Items);
		return [
			'error' => false,
			'name' => $Directory->getBaseName(),
			'path' => $Directory->toPath()->exclude($this->getResourceRoot())->toString(),
			'is_root' => $Directory->toPath()->isEqualTo($this->getResourceRoot()),
			'items' => array_values($Items),
			'type' => 'folder'
		];
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function upload() {
		if (!in_array($type = Input::get('type', 'media'), [
			self::RT_MEDIA, self::RT_BLOG, self::RT_AUTHORS])){
				throw new \Exception('Unsupported upload type!');
		}

		$Directory = $this->getResourceRoot($type)
			->append(Input::get('context'))->toDirectory();

		if (!$Directory->toPath()->isChildOf($this->getResourceRoot($type))){
			throw new \Exception('Access denied!');
		}

		if (!Input::hasFile('files')) {
			throw new \Exception('Upload queue is empty!');
		}

		if (!is_array(Input::file('files')) || count(Input::file('files')) > 1){
			throw new \Exception('Invalid request!');
		}

		$File = Input::file('files')[0];
		$File->move($Directory->toString(), $File->getClientOriginalName());

		$File = $Directory->toPath()
			->append($File->getClientOriginalName())->toFile();

		Bus::dispatch(new ProcessMedia($File, $type));

		return [
			'error' => false,
			'name' => $File->getBaseName(),
			'path' => $Directory->toPath()->toString(),
			'url' =>  $File->toPath()->exclude(App::scope()->path->append('resources'))->toString(),
			'size' => $File->getSize(),
		];
	}

	/**
	 * @throws \Exception
	 */
	public final function createFolder() {
		$Directory = $this->getResourceRoot()
			->append(Input::get('context'))->toDirectory();

		if (!$Directory->toPath()->isChildOf($this->getResourceRoot())){
			throw new \Exception('Access denied!');
		}

		$Destination = $Directory->toPath()->append('New Folder')->try(function(){
			throw new \Exception('The directory already exists!');
		}, Path::TIF_EXIST)->forceDirectory();

		return [
			'error' => false,
			'name' => $Destination->getBaseName(),
			'path' => $Destination->toPath()->toString(),
			'size' => $Destination->getSize(),
		];
	}

	public final function proxyResource($url){
		_dumpe($url);
	}

}
