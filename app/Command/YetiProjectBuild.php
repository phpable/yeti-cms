<?php
namespace Yeti\Main\Command;

use \Yeti\Main\Command\Abstracts\ACommand;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Config;

use \Yeti\Core\Model\Page;

use \Yeti\Main\Building\Builders\ProjectBuilder;

use \Able\Helpers\Src;

use \Able\IO\Path;
use \Able\IO\Directory;

class YetiProjectBuild extends ACommand {

	/**
	 * @var bool
	 */
	protected static $scopable = true;

	/**
	 * @var string
	 */
	protected static $prefix = 'build';

	/**
	 * @var string
	 */
	protected $signature = 'yeti:project:build {--target=} {--silent}';

	/**
	 * @return void
	 * @throws \Exception
	 */
	public final function handle(): void {
		$Directory = Path::create(Config::get('building.destination'),
			App::scope()->name, 'pages')->forceDirectory();

		$Directory->clear();

		$count = 0;
		if (is_null($this->option('target'))) {
			if (count($Pages = Page::where('is_hidden', '<>', 1)->get()) < 1){
				$this->warn('Nothing to build!');
			}else {
				foreach ($Pages as $Page) {
					$this->saveProcessInfo(ceil(++$count * 100 / count($Pages)));
					$this->build($Page, $Directory, $this->getProcessInfo());
				}
			}

		} else {
			if (is_null($Target = Page::where('name', '=', $this->option('target'))->first())) {
				throw new \Exception('Invalid target!');
			}

			$this->build($Target, $Directory);
			$count++;
		}

		App::scope()->update(['builded_at' => date('Y-m-d H:i:s')]);
		if (!$this->option('silent')) {
			$this->info(sprintf('Total: %s', $count));
		}
	}

	/**
	 * @param Page $Page
	 * @param Directory $Directory
	 * @param int $progress
	 * @throws \Exception
	 */
	private final function build(Page $Page, Directory $Directory, int $progress = null): void {
		try {
			$timing = microtime(true);
			$class = '\Yeti\Main\Building\Builders\\' . Src::tcm($Page->builder);

			if (!class_exists($class)) {
				throw new \Exception('Undefined builder!');
			}

			(new $class($Page, $Page->arguments))->build($Directory);
			$timing = microtime(true) - $timing;

			if (!$this->option('silent')) {
				$this->info(sprintf('[%1$03d%% Done] [%2$fs] Builded page "%3$s" as "%4$s"',
					$progress ?? 100, $timing, $Page->name, $Page->url));
			}
		} catch (\Throwable $Exception){
			if (!$this->option('silent')) {

				$this->warn(sprintf('[%1$03d%% Done] Building failed for page "%2$s": %3$s',
					$progress ?? 100, $Page->name, $Exception->getMessage()));
			}
		}
	}

}
