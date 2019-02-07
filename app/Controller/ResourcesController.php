<?php
namespace Yeti\Main\Controller;

use \Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;

use \Yeti\Main\Controller\Abstracts\AController;

use \Able\Helpers\Arr;
use \Able\Helpers\Str;

use \Able\IO\Path;
use \Able\IO\Directory;

class ResourcesController extends AController {

	public final function proxyResource(string $type, string $name){
		$Path = App::scope()->path->append('resources', $type);

		if (!$Path->isExists()){
			return abort(444);
		}

		try{
			$File = $Path->append($name)->toFile();

			return response($File->getContent())->header('Content-Type',
				mime_content_type($File->toString()));

		}catch (\Exception $Exception) {
			return abort(404);
		}
	}

}
