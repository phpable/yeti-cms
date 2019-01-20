<?php
namespace Yeti\Main\Controller;

use \Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;

use \Illuminate\View\Vsiew;

use \Yeti\Main\Controller\Abstracts\AController;

use \Able\Helpers\Arr;
use \Able\Helpers\Str;

use \Able\IO\Path;
use \Able\IO\Directory;

class FilesController extends AController {

	/**
	 * @return Path
	 */
	private function getResourceRoot(): Path {
		return $this->RootCache = App::scope()->path->append('resources', 'media')->try(function () {
			throw new \Exception('Resource root is not exist or not writable!');
		}, Path::TIF_NOT_DIRECTORY | Path::TIF_NOT_WRITABLE);
	}

	/**
	 * @return View
	 */
	public function manager() {
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
		$Directory = $this->getResourceRoot()
			->append(Input::get('context'))->toDirectory();

		if (!$Directory->toPath()->isChildOf($this->getResourceRoot())){
			throw new \Exception('Access denied!');
		}

		if (Input::hasFile('files')) {
			if (!is_array(Input::file('files')) || count(Input::file('files')) > 1){
				throw new \Exception('Invalid request!');
			}

			$File = Input::file('files')[0];
			$File->move($Directory->toString(), $File->getClientOriginalName());
		}

		$File = $Directory->toPath()
			->append($File->getClientOriginalName())->toFile();

		return [
			'error' => false,
			'name' => $File->getBaseName(),
			'path' => $Directory->toPath()->toString(),
			'size' => $File->getSize(),
		];
	}

	/**
	 * @throws \Exception
	 */
	public final function createFolder(){
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

}
