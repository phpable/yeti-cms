<?php
namespace Yeti\Main\Command;

use \Illuminate\Console\Command;
use \Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\File;

use \Yeti\Main\Model\Module;
use \Yeti\Main\Model\Project;

use \Able\Helpers\Arr;
use \Able\Helpers\Src;
use \Able\Helpers\Str;

class YetiModulesUpdate extends Command {

	/**
	 * @var string
	 */
	protected $signature = 'yeti:modules:update';

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public final function handle(): void {
		try{

			/**
			 * Generate the routes file.
			 */
			$Out = "<?\n";
			$count = 0;

			foreach (Module::getActive() as $Module){
				$Out .= "Route::group(['middleware' => ['auth', 'scope'], 'prefix' => '"
					. $Module->maintainer . "/" . $Module->name . "'], function () {\n";

				try {
					if (is_dir($path = module_path($Module, 'src/Controller'))) {
						foreach (scandir($path) as $file) {
							if (!preg_match('/^\.+/', $file)) {
								include_once($path . '/' . $file);

								$class = '\\' . Src::tcm($Module->maintainer) . '\\' . Src::tcm($Module->name)
									. '\Controller\\' . preg_replace('/\.php$/', null, $file);

								if (!class_exists($class)) {
									throw new \Exception('Undefined or invalid controller class "' . $class . '"!');
								}

								foreach (array_diff(get_class_methods($class), get_class_methods(get_parent_class($class))) as $action){

									$Params = array_map(function($Param){ return '{'. $Param->getName() .
										'}'; }, (new \ReflectionClass($class))->getMethod($action)->getParameters());

									$Out .= "\tRoute::any('" . Str::join('/', Src::fcm(Src::rns($class), '-') . "/" . Src::fcm($action, '-'), implode('/', $Params)) . "', ['as' => '"
										. $Module->maintainer . "@" . $Module->name . ":" . Src::fcm(Src::rns($class), '-') . "." . Src::fcm($action, '-') . "', \n\t\t'uses' => '"
											. $class . "@" . $action . "'])->where('params', '.*');\n";

									$count++;
								}
							}
						}
					}
				}catch (\Exception $Exception){
					$Module->update(['status'
						=> Module::MS_CORRUPTED]);

					throw $Exception;
				}

				$Out .= "});\n\n";
			}
			$file = file_put_contents(temporary_path('routes.php'), $Out);
			$this->info(sprintf('%1$ 2d route(s) imported.', $count));

			/**
			 * Generate the psr4 file.
			 */
			$Out = "<?\nreturn [\n";
			$count = 0;

			foreach (Module::getActive() as $Module) {

				$Out .= "\t'" . Src::tcm($Module->maintainer) . '\\'
					. Src::tcm($Module->name) . "\\\\' => ['" . module_path($Module, 'src') . "'],\n" ;

				$count++;
			}
			$Out .= "];";
			$file = file_put_contents(temporary_path('import.php'), $Out);
			$this->info(sprintf('%1$ 2d path(s) imported.', $count));

			/**
			 * Generate the resourses file.
			 */
			$Out = "<?\nreturn [\n";
			$count = 0;

			foreach (Module::getActive() as $Module) {

				$Out .= "\t'" . strtolower($Module->maintainer) . '@'
					. strtolower($Module->name) . "' => '" . module_path($Module, 'resources/views') . "',\n" ;

				$count++;
			}
			$Out .= "];";
			$file = file_put_contents(temporary_path('resources.php'), $Out);
			$this->info(sprintf('%1$ 2d view(s) imported.', $count));

		}catch (\Exception $Exception){
			$this->error($Exception->getMessage());
		}
	}
}
