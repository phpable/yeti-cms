<?php
namespace Yeti\Main\Command;

use \Illuminate\Console\Command;

use \Yeti\Main\Command\Abstracts\ACommand;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Log;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Layout;
use \Yeti\Core\Model\Snippet;
use \Yeti\Core\Model\Template;

use \Yeti\Main\Model\Abstracts\ITemplatable;

use \Able\IO\Path;
use \Able\IO\Writer;
use \Able\IO\Directory;

use \Able\Helpers\Arr;
use \Able\Helpers\Src;

use \Closure;
use \Exception;
use \Generator;


class YetiCompose extends ACommand {

	/**
	 * @var bool
	 */
	protected static $scopable = true;

	/**
	 * @var string
	 */
	protected $signature = 'yeti:compose {scope} {destination} {--compact=} {--clear}';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Compose entities from the database into a filesystem.';

	/**
	 * Execute the console command.
	 * @throws Exception
	 */
	public function handle(): void {
		$count = 0;

		try {
			$Directory = (new Path($this->argument('destination'),
				App::scope()->name))->forceDirectory();

			if (!$Directory->isEmpty()) {
				if (!$this->option('clear')) {
					throw new Exception('The destination directory is not empty!');
				}

				$Directory->clear();
			}

			foreach (Layout::all() as $Layout) {
				if ($this->compose($Directory, $Layout)) {

					$this->info(sprintf("Exported: layout #%s", $Layout->id));
				}
			}

			$Directory = (new Path($this->argument('destination'),
				App::scope()->name, 'pages'))->forceDirectory();

			foreach (Page::all() as $Page) {
				if ($this->compose($Directory, $Page)) {

					$this->info(sprintf("Exported: page #%s", $Page->id));
				}

				$Directory->toPath()->append(sprintf('%s.json', $Page->name))
					->forceFile()->rewrite(json_encode(Arr::only($Page->toArray(), 'title', 'description', 'url'), JSON_PRETTY_PRINT));
			}

			$Directory = (new Path($this->argument('destination'),
				App::scope()->name, 'snippets'))->forceDirectory();

			foreach (Snippet::all() as $Snippet) {
				if ($this->compose($Directory, $Snippet)) {

					$this->info(sprintf("Exported: snippet #%s", $Snippet->id));
				}
			}
		} catch (\Throwable $Exception) {
			$this->error($Exception->getMessage());
		}
	}

	/**
	 * @param ITemplatable $Item
	 * @return array
	 *
	 * @throws Exception
	 */
	public final function collectLayouts(ITemplatable $Item): array {
		$Commons = [];
		foreach ($Item->templates()
			->orderBy('name')->get() as $Template) {

			if (in_array($Template->type, [
				'css',
				'js'
			])) {

				$Commons = Arr::improve($Commons, $Template->type, $Template->source);
			}
		}

		$Fragments = [];
		foreach ($Item->templates()
			->orderBy('name')->get() as $Template) {

			if ($Template->type == 'html') {
				$Fragments = Arr::improve($Fragments, $Template->name, 'main', $Template->source);

				foreach ($Commons as $name => $Contents) {
					foreach ($Contents as $content) {

						$Fragments = Arr::improve($Fragments, $Template->name, $name, $content);
					}
				}
			}
		}

		return $Fragments;
	}

	/**
	 * @param Page $Item
	 * @return array
	 *
	 * @throws Exception
	 */
	public final function collectPages(Page $Item): array {
		$Fragments = [];

		if (!is_null($Item->layout)) {
			$Fragments = Arr::improve($Fragments, '@', sprintf("@extends('%s')\n",
				!is_null($Item->template)

					? $Item->template->name : 'main'));
		}

		foreach ($Item->templates()
			->orderBy('name')->get() as $Template) {

				$Fragments = Arr::improve($Fragments,
					in_array($Template->type, [
						'css',
						'js'
					])

				? $Template->type : $Template->name, $Template->source);
		}

		return [
			$Item->name => $Fragments
		];
	}

	/**
	 * @param Snippet $Item
	 * @return array
	 *
	 * @throws Exception
	 */
	public final function collectSnippets(Snippet $Item): array {
		$Fragments = [];

		foreach ($Item->templates()
			->orderBy('name')->get() as $Template) {

				$Fragments = Arr::improve($Fragments,
					in_array($Template->type, [
						'css',
						'js'
					])

				? $Template->type : $Template->name, $Template->source);
		}

		return [
			$Item->name => $Fragments
		];
	}

	/**
	 * @param Directory $Directory
	 * @param ITemplatable $Item
	 * @return bool
	 *
	 * @throws Exception
	 */
	public final function compose(Directory $Directory, ITemplatable $Item): bool {
		if (!method_exists($this,

			$method = sprintf("collect%ss",
				ucfirst(Src::tcm(Src::rns(get_class($Item))))))) {

					throw new Exception(sprintf('Undefined type %s!', get_class($Item)));
		}

		$Fragments = $this->{$method}($Item);
		if (count($Fragments) > 0) {

			foreach ($Fragments as $name => $Fragment) {
				ksort($Fragment);

				$File = $Directory->toPath()
					->append(sprintf("%s.blade.php", $name))->forceFile();

				foreach ($Fragment as $token => $Sources) {
					$File->toWriter()
						->write($this->wrap(implode("\n", $Sources), $token));
				}
			}
		}

		return count($Fragments) > 0;
	}

	/**
	 * @param string $content
	 * @param string $name
	 *
	 * @return Generator
	 */
	private function wrap(string $content, string $name): Generator {
		if ($name !== '@') {
			yield sprintf('@section(\'%s\')', $name);
		}

		if ($name == 'js') {
			yield '<script type="text/javascript">';
		}

		if (!empty(trim($content))) {
			yield $content;
		}

		if ($name == 'js') {
			yield '</script>';
		}

		if ($name !== '@') {
			yield "@stop\n";
		}
	}
}
