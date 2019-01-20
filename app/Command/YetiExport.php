<?php
namespace Yeti\Main\Command;

use \Illuminate\Console\Command;

use \Yeti\Main\Command\Abstracts\ACommand;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Log;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Layout;
use \Yeti\Core\Model\Snippet;

use \Able\IO\Path;
use \Able\IO\Directory;

class YetiExport extends ACommand {

	/**
	 * @var bool
	 */
	protected static $scopable = true;

	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'yeti:export {scope} {destination}';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Import entities from the database into a filesystem.';

	/**
	 * Execute the console command.
	 * @throws \Exception
	 */
	public function handle(): void {
		$count = 0;

		try {
			$Directory = (new Path($this->argument('destination'),
				App::scope()->name))->forceDirectory();

			if (!$Directory->isEmpty()) {
				throw new \Exception('The destination directory is not empty!');
			}

			foreach (Page::all() as $Page) {
				$Destination = $Directory->toPath()->append('pages', 'page_'
					. $Page->id)->forceDirectory();

				foreach ($Page->templates as $Template) {
					$Destination->toPath()->append('template_'
						. $Template->id . '.' . $Template->type)->forceFile()->rewrite((string)$Template->source);

					$count++;
				}

				$this->info("Exported: page #" . $Page->id);
			}

			foreach (Layout::all() as $Layout) {
				$Destination = $Directory->toPath()->append('layouts', 'layout_'
					. $Layout->id)->forceDirectory();

				foreach ($Layout->templates as $Template) {
					$Destination->toPath()->append('template_'
						. $Template->id . '.' . $Template->type)->forceFile()->rewrite((string)$Template->source);

					$count++;
				}

				$this->info("Exported: layout #" . $Layout->id);
			}

			foreach (Snippet::all() as $Snippet) {
				$Destination = $Directory->toPath()->append('snippets', 'snippet_'
					. $Snippet->id)->forceDirectory();

				foreach ($Snippet->templates as $Template) {
					$Destination->toPath()->append('template_'
						. $Template->id . '.' . $Template->type)->forceFile()->rewrite((string)$Template->source);

					$count++;
				}

				$this->info("Exported: snippet #" . $Snippet->id);
			}

			$this->warn("Total: " . $count);
		} catch (\Throwable $Exception) {
			$this->error($Exception->getMessage());
		}
	}
}
