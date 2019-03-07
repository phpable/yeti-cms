<?php
namespace Yeti\Main\Controller;

use \Illuminate\Http\Request;
use \Illuminate\Http\Response;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\File;
use \Illuminate\Support\Facades\View;
use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Artisan;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Layout;
use \Yeti\Core\Model\Constant;

use \Yeti\Main\Model\Project;
use \Yeti\Main\Building\Builders;

use \Yeti\Main\Controller\Abstracts\AController;

use \Able\IO\Path;
use \Able\Helpers\Arr;

class DeployController extends AController {

	/**
	 * @var Path
	 */
	private static $pidFileCache = null;

	/**
	 * @return Path
	 * @throws \Exception
	 */
	private static function buildPidFilePath(): Path {
		if (is_null(self::$pidFileCache)) {

			self::$pidFileCache = Path::create(base_path('storage'), 'pids',
				md5(App::scope()->name) . 'deploy.pid');
		}

		return self::$pidFileCache;
	}

	/**
	 * @var Path
	 */
	private static $logFileCache = null;

	/**
	 * @return Path
	 * @throws \Exception
	 */
	private static function buildLogFilePath(){
		if (is_null(self::$logFileCache)) {

			self::$logFileCache = Path::create(base_path('storage'), 'logs',
				md5(App::scope()->name) . '.deploy.log');
		}

		return self::$logFileCache;
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function deploy(){
		try {
			if (self::buildPidFilePath()->isExists()){
				throw new \Exception('Process is already running!');
			}

//			Artisan::call('yeti:project:deploy', [
//				'scope' => App::scope()->name,
//				'--force' => true,
//				'--rebuild' => true,
//				'--silent' => true,
//			]);

			exec(base_path() . '/artisan yeti:project:deploy ' . App::scope()->name
				. ' --pid=' . self::buildPidFilePath() . ' --force --rebuild --silent > ' . self::buildLogFilePath() . ' 2>&1 &');

			return['status' => true,
				'url' => route('yeti@main:deploy.check')];

		} catch (\Throwable $Exception) {
			Log::error($Exception);

			return ['status' => false,
				'error' => $Exception->getMessage()];
		}
	}

	public function check() {
		try {
			$Data = preg_split('/;+/', self::buildPidFilePath()->try(function (Path $Path) {
				throw new \Exception('Process not found!');
			}, Path::TIF_NOT_FILE)->toFile()->getContent(), -1, PREG_SPLIT_NO_EMPTY);

			return [
				'status' => true,
				'action' => Arr::value($Data, 0, 'initialize'),
				'percent' => Arr::value($Data, 1, 0)
			];
		}catch (\Throwable $Exception){
			Log::error($Exception);

			return['status' => false,
				'error' => $Exception->getMessage()];
		}
	}
}



