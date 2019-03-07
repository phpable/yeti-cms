<?php
namespace Yeti\Main\Command;

use \Yeti\Main\Command\Abstracts\ACommand;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Config;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Layout;
use \Yeti\Core\Model\Snippet;
use \Yeti\Core\Model\Template;

use \Yeti\Main\Building\Builder;

use \Able\Helpers\Src;
use \Able\Helpers\Str;
use \Able\Helpers\Arr;

use \Able\IO\Path;
use \Able\IO\Directory;

use \Exception;

class YetiProjectMigrate extends ACommand {

	/**
	 * @var bool
	 */
	protected static $scopable = true;

	/**
	 * @var string
	 */
	protected $signature = 'yeti:project:migrate';

	/**
	 * @return void
	 * @throws Exception
	 */
	public final function handle(): void {
		try{
			$Destination = Path::create(base_path(sprintf('data/%s/', App::scope()->name)))
				->forceDirectory() ;

			$this->migrateLayouts($Destination);
			$this->migrateSnippets($Destination);
			$this->migratePages($Destination);

		} catch (\Throwable $Exception) {
			$this->exception($Exception);
		}
	}

	/**
	 * @param Directory $Directory
	 * @throws Exception
	 */
	public final function migrateLayouts(Directory $Directory): void {
			$Destination = $Directory->toPath()->append('layouts')
				->forceDirectory() ;

			$Destination->clear();

			$count = 0;

			$this->comment(str_pad('-', 24, '-'));
			$this->comment(sprintf('Layouts found: %s', Layout::count()));
			$this->comment(str_pad('-', 24, '-'));

			foreach (Layout::all() as $Layout) {
				$this->info(sprintf('Layout: %s', $Layout->name));

				$Storage = $Destination->toPath()
					->append($Layout->name)->forceDirectory();

				$Storage->clear();

				foreach ($Layout->templates as $Template) {
					$Storage->toPath()
						->append(Str::join('.', $Template->name,
							$Template->type == 'html' ? 'sabre' : $Template->type))
						->forceFile()->rewrite((string)$Template->source);

					$this->comment(sprintf("%s => %s", $Template->type, $Template->name));
				}

				$count++;
			}

			$this->info(sprintf("Total: %s", $count));
	}

	/**
	 * @param Directory $Directory
	 * @throws Exception
	 */
	public final function migrateSnippets(Directory $Directory): void {
			$Destination = $Directory->toPath()->append('snippets')
				->forceDirectory() ;

			$Destination->clear();

			$count = 0;

			$this->comment(str_pad('-', 24, '-'));
			$this->comment(sprintf('Snippets found: %s', Snippet::count()));
			$this->comment(str_pad('-', 24, '-'));

			foreach (Snippet::all() as $Snippet) {
				$this->info(sprintf('Snippet: %s', $Snippet->name));

				$Storage = $Destination->toPath()
					->append($Snippet->name)->forceDirectory();

				$Storage->clear();

				$Storage->toPath()
					->append('.metadata.json')->forceFile()
					->rewrite(json_encode(Arr::only($Snippet->toArray(), 'params'),
						JSON_PRETTY_PRINT));

				foreach ($Snippet->templates as $Template) {
					$Storage->toPath()
						->append(Str::join('.', $Template->name,
							$Template->type == 'html' ? 'sabre' : $Template->type))
						->forceFile()->rewrite((string)$Template->source);

					$this->comment(sprintf("%s => %s", $Template->type, $Template->name));
				}

				$count++;
			}

			$this->info(sprintf("Total: %s", $count));
	}

	/**
	 * @param Directory $Directory
	 * @throws Exception
	 */
	public final function migratePages(Directory $Directory): void {
			$Destination = $Directory->toPath()->append('pages')
				->forceDirectory();

			$Destination->clear();

			$count = 0;

			$this->comment(str_pad('-', 24, '-'));
			$this->comment(sprintf('Pages found: %s', Page::count()));
			$this->comment(str_pad('-', 24, '-'));

			foreach (Page::all() as $Snippet) {
				$this->info(sprintf('Page: %s', $Snippet->url));

				$Storage = $Destination->toPath()
					->append($Snippet->name)->forceDirectory();

				$Storage->clear();

				$Storage->toPath()
					->append('.metadata.json')->forceFile()
					->rewrite(json_encode(Arr::each(Arr::only($Snippet->toArray(),
						'title', 'description', 'url',
						'config', 'in_sitemap', 'is_hidden'),
					function($key, $value){
						return in_array($key, ['in_sitemap', 'is_hidden']) ? true : $value;
				}), JSON_PRETTY_PRINT));

				foreach ($Snippet->templates as $Template) {
					$Storage->toPath()
						->append(Str::join('.', $Template->name,
							$Template->type == 'html' ? 'sabre' : $Template->type))
						->forceFile()->rewrite($Template->source);

					$this->comment(sprintf("%s => %s", $Template->type, $Template->name));
				}

				$count++;
			}

			$this->info(sprintf("Total: %s", $count));
	}
}
