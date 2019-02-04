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

use \Able\IO\Path;
use \Able\IO\Directory;

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
			Path::create(temporary_path('routes.php'))->forceFile()->purge()
				->toWriter()->write($this->collectRouters());

			/**
			 * Generate the psr4 file.
			 */
			Path::create(temporary_path('import.php'))->forceFile()->purge()
				->toWriter()->write($this->collectImports());

			/**
			 * Generate the resourses file.
			 */
			Path::create(temporary_path('resources.php'))->forceFile()->purge()
				->toWriter()->write($this->collectResources());

		}catch (\Exception $Exception){
			$this->error($Exception->getMessage());
		}
	}

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public final function collectRouters(): \Generator {
		$this->info('Collecting router...');
		yield "<?\n";

		$count = 0;
		foreach (Module::whereActive()->get() as $Module) {
			yield "Route::group(['middleware' => ['auth', 'scope'], 'prefix' => '"
				. $Module->maintainer . "/" . $Module->name . "'], function () {\n";

			try {
				foreach ($Module->getPath()->append('src', 'Controller')
							 ->toDirectory()->filter('[!.]*') as $Path) {

					include_once($Path->toString());

					$class = '\\' . $Module->getNamespace()
						. '\Controller\\' . preg_replace('/\.php$/', null, $Path->getEnding());

					if (!class_exists($class)) {
						throw new \Exception('Undefined or invalid controller class "' . $class . '"!');
					}

					foreach (array_diff(get_class_methods($class),
						get_class_methods(get_parent_class($class))) as $action) {

						$Params = array_map(function ($Param) {
							return '{' . $Param->getName() .
								'}';
						}, (new \ReflectionClass($class))->getMethod($action)->getParameters());

						yield "\tRoute::any('" . Str::join('/', Src::fcm(Src::rns($class), '-') . "/" . Src::fcm($action, '-'), implode('/', $Params)) . "', ['as' => '"
							. $Module->maintainer . "@" . $Module->name . ":" . Src::fcm(Src::rns($class), '-') . "." . Src::fcm($action, '-') . "', \n\t\t'uses' => '"
							. $class . "@" . $action . "'])->where('params', '.*');\n";

						$count++;
					}

				}
			} catch (\Exception $Exception) {

				$Module->update(['status'
				=> Module::MS_CORRUPTED]);

				$this->warn($Exception->getMessage());
				continue;
			} finally {
				yield "});\n\n";
			}
		}

		$this->info(sprintf("\t%1\$ 2d route(s) imported.", $count));
	}

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public final function collectImports(): \Generator {
		$this->info('Collecting imports...');
		yield "<?\nreturn [\n";

		$count = 0;
		try {
			foreach (Module::whereActive()->get() as $Module) {
				try {
					yield "\t'" . Src::tcm($Module->maintainer) . '\\'
						. Src::tcm($Module->name) . "\\\\' => ['" . module_path($Module, 'src') . "'],\n";

					$count++;
				} catch (\Exception $Exception) {

					$Module->update(['status'
					=> Module::MS_CORRUPTED]);

					$this->warn($Exception->getMessage());
					continue;
				}
			}
		} finally {
			yield "];\n\n";
		}

		$this->info(sprintf("\t%1\$ 2d path(s) imported.", $count));
	}


	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public final function collectResources(): \Generator {
		$this->info('Collecting resources...');
		yield "<?\nreturn [\n";

		$count = 0;
		try {
			foreach (Module::whereActive()->get() as $Module) {
				try {

					foreach (Path::create(module_path($Module),
						'resources')->toDirectory()->filter('[^.]*') as $Path){
							$token = $Module->getMnemonic();

							if ($Module->manifest('resources.default') != $Path->getEnding()) {
								$token .= '[' . $Path->getEnding() . ']';
							}

							yield "\t'" . $token .  "' => '" . $Path->toString() . "',\n" ;

						$count++;
					}
				} catch (\Exception $Exception) {

					$Module->update(['status'
					=> Module::MS_CORRUPTED]);

					$this->warn($Exception->getMessage());
					continue;
				}
			}
		} finally {
			yield "];\n\n";
		}

		$this->info(sprintf("\t%1\$ 2d resource(s) imported.", $count));
	}
}
