<?php
namespace Yeti\Main\Command;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\Config;

use \Yeti\Main\Command\Abstracts\ACommand;

use \Yeti\Core\Model\Resource;

use \Able\IO\Path;
use \Able\IO\File;
use \Able\IO\Directory;

use \Able\Helpers\Arr;

class YetiResourcesCheck extends ACommand {

	/**
	 * @var bool
	 */
	protected static $scopable = true;

	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'yeti:resources:check';

	/**
	 * @var array
	 */
	private static $Categories = ['style' => 'styles', 'script' => 'scripts',
		'media' => 'media'];

	/**
	 * @var array
	 */
	private static $Mimetypes = ['style' => 'text/css',
		'script' => 'text/javascript'];

	/**
	 * @return void
	 * @throws \Exception
	 */
	public function handle(): void {
		$Resources = Path::create(Config::get('building.resources'),
			App::scope()->name);

		$total = 0;
		foreach (self::$Categories as $type => $fragment){
			$this->warn(sprintf('Checking of "%s" in %s/%s', $type, $Resources, $fragment));

			try{
				$total += $this->scanResourseDirectory($type, $Resources->toPath()
					->append($fragment)->try(function(){

						throw new \Exception('The resources path is not exists or not readable!');
				}, Path::TIF_NOT_DIRECTORY)->toDirectory());
			}catch (\Throwable $Exception){
				$this->exception($Exception);
				continue;
			}
		}

		$this->info(sprintf("Total: %d", $total));
	}

	/**
	 * @param string $category
	 * @param Directory $Directory
	 * @return int
	 * @throws \Exception
	 */
	protected final function scanResourseDirectory(string $category, Directory $Directory): int {
		$count = 0;

		foreach($Directory->list() as $Path){
			if (!$Path->isDot()){
				if ($Path->isDirectory()){
					$count += $this->scanResourseDirectory($category, $Path->toDirectory());

				} else {
					if (Resource::where('name', '=', $Path->getEnding())
						->where('category', '=', $category)->count() < 1) {
							$this->info(sprintf("Found: %s", $Path));

							$this->represent($category, $Path->toFile());
							$count++;
					}
				}
			}
		}

		return $count;
	}

	/**
	 * @param string $category
	 * @param File $File
	 * @return void
	 * @throws \Exception
	 */
	protected final function represent(string $category, File $File): void {
		$Resource = Resource::create([
			'name' => $File->getBaseName(),
			'type' => self::$Mimetypes[$category] ?? $File->getMimeType(),
			'category' => $category,
			'path' => $File->toString()
		]);
	}


}
