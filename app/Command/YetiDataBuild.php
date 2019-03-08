<?php
namespace Yeti\Main\Command;

use \Yeti\Main\Command\Abstracts\ACommand;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Config;

use \Yeti\Core\Model\Page;

use \Yeti\Main\Building\Builder;

use \Able\Helpers\Src;

use \Able\IO\Path;
use \Able\IO\Directory;

class YetiDataBuild extends ACommand {

	/**
	 * @var bool
	 */
	protected static $scopable = true;

	/**
	 * @var string
	 */
	protected static $prefix = 'prepare';

	/**
	 * @var string
	 */
	protected $signature = 'yeti:data:build {--silent}';

	/**
	 * @return void
	 * @throws \Exception
	 */
	public final function handle(): void {
		$Directory = Path::create(Config::get('building.destination'),
			App::scope()->name, 'data')->forceDirectory();

		$Directory->clear();
		$count = 0;

		$Objects = App::scope()->objects;
		if (count($Objects) < 1){
			$this->warn('Nothing to build!');
		}else {
			foreach ($Objects as $Share) {
				$this->saveProcessInfo(ceil(++$count * 100 / count($Objects)));
				$this->export($Directory, $Share, (int)$this->getProcessInfo());
			}
		}

		App::scope()->update(['builded_at' => date('Y-m-d H:i:s')]);
		if (!$this->option('silent')) {
			$this->info(sprintf('Total: %s', $count));
		}
	}

	private final function export(Directory $Distination, array $Share, int $progress = null): void {
		try {
			$timing = microtime(true);

			$Target = (new Path($Distination, strtolower($Share['alias'])))->forceDirectory();
			$Target->clear();

			App::exporter()->export($Share['item'])->save($Target,
				$Share['type'], $Share['value']);

			$timing = microtime(true) - $timing;

			if (!$this->option('silent')) {
				$this->info(sprintf('[%1$03d%% Done] [%2$fs] Exported "%3$s" as "%4$s"',
					$progress ?? 100, $timing, $Share['item'], $Share['alias']));
			}
		} catch (\Throwable $Exception) {
			if (!$this->option('silent')) {
				$this->warn(sprintf('[%1$03d%% Done] Export failed for "%2$s": %3$s',
					$progress ?? 100, $Share['alias'], $Exception->getMessage()));
			}
		}
	}
}
