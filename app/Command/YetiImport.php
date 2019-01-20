<?php
namespace Yeti\Main\Command;

use \Illuminate\Console\Command;

use \Yeti\Main\Command\Abstracts\ACommand;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Log;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Layout;
use \Yeti\Core\Model\Template;
use \Yeti\Core\Model\Snippet;

use \Able\IO\Path;
use \Able\IO\Directory;

use \Able\Helpers\Src;
use \Able\Reglib\Regexp;

class YetiImport extends ACommand {

	/**
	 * @var bool
	 */
	protected static $scopable = true;

	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'yeti:import {scope} {source}';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Export entities from the filesystem into a database.';

	/**
	 * Execute the console command.
	 * @throws \Exception
	 */
	public function handle(): void {
		try {
			foreach (Path::create($this->argument('source'))->append(App::scope()->name)->try(function(){
					throw new \Exception('Import directory is not exists or not readable!');
				}, Path::TIF_NOT_DIRECTORY)->toDirectory()->list() as $Path) {

					if (!$Path->isDot() || !$Path->isDirectory()) {
						if (method_exists($this, $method = 'import' . Src::tcm($Path->getEnding()))) {
							$this->{$method}($Path->toDirectory());
						} else {
							throw new \Exception('Invalid export path: ' . $Path->toString());
						}
					}
			}

			$this->info('Finished');
		}catch (\Exception $Exception){
			$this->exception($Exception);
		}
	}

	/**
	 * @param Directory $Directory
	 * @throws \Exception
	 */
	public final function importPages(Directory $Directory){
		$count = 0;
		$errors = 0;

		$this->warn('Importing sources from: ' . $Directory->toString());
		foreach ($Directory->filter('*/page_*') as $Element){
			try {
				if (!$Element->isDot()) {
					if (!is_null($Page = Page::find(Regexp::create('/[0-9]+$/')
						->take($Element->getEnding())))) {

							foreach ($Element->toDirectory()->filter('*/template_*.*') as $Path) {
								if (!is_null($Template = $Page->templates()->find(Regexp::create('/([0-9]+)\.\w+$/')
									->take($Path->getEnding(), 1)))){

										$Template->update(['source' => $Path->toFile()->getContent()]);
										$this->info('Imported: source #' . $Template->id . ' of page #' . $Page->id);

										$count++;
								}
							}

					}
				}
			}catch (\Throwable $Exception){
				$this->error("Corrupted: path " . $Directory->toString());
				$errors++;

				continue;
			}
		}

		$this->warn('Total: ' . $count . ', errors: ' . $errors);
	}

	/**
	 * @param Directory $Directory
	 * @throws \Exception
	 */
	public final function importSnippets(Directory $Directory){
		$count = 0;
		$errors = 0;

		$this->warn('Importing sources from: ' . $Directory->toString());
		foreach ($Directory->filter('*/snippet_*') as $Element){
			try {
				if (!$Element->isDot()) {
					if (!is_null($Snippet = Snippet::find(Regexp::create('/[0-9]+$/')
						->take($Element->getEnding())))) {

							foreach ($Element->toDirectory()->filter('*/template_*.*') as $Path) {
								if (!is_null($Template = $Snippet->templates()->find(Regexp::create('/([0-9]+)\.\w+$/')
									->take($Path->getEnding(), 1)))){
										$Template->update(['source' => $Path->toFile()->getContent()]);

										$this->info('Imported: source #' . $Template->id . ' of snippet #' . $Snippet->id);
										$count++;
								}
							}
					}
				}
			}catch (\Throwable $Exception){
				$this->error("Corrupted: path " . $Directory->toString());
				$errors++;

				continue;
			}
		}

		$this->warn('Total: ' . $count . ', errors: ' . $errors);
	}



	/**
	 * @param Directory $Directory
	 * @throws \Exception
	 */
	public final function importLayouts(Directory $Directory){
		$count = 0;
		$errors = 0;

		$this->warn('Importing sources from: ' . $Directory->toString());
		foreach ($Directory->filter('*/layout_*') as $Element){
			try {
				if (!$Element->isDot()) {
					if (!is_null($Layout = Layout::find(Regexp::create('/[0-9]+$/')
						->take($Element->getEnding())))) {

							foreach ($Element->toDirectory()->filter('*/template_*.*') as $Path) {
								if (!is_null($Template = $Layout->templates()->find(Regexp::create('/([0-9]+)\.\w+$/')
									->take($Path->getEnding(), 1)))){
										$Template->update(['source' => $Path->toFile()->getContent()]);

										$this->info('Imported: source #' . $Template->id . ' of layout #' . $Layout->id);
										$count++;
								}
							}
					}
				}
			}catch (\Throwable $Exception){
				$this->error("Corrupted: path " . $Directory->toString());
				$errors++;

				continue;
			}
		}

		$this->warn('Total: ' . $count . ', errors: ' . $errors);
	}
}
