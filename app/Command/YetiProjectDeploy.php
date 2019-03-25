<?php
namespace Yeti\Main\Command;

use \Yeti\Main\Command\Abstracts\ACommand;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\Config;

use \MatthiasMullie\Minify\CSS;
use \MatthiasMullie\Minify\JS;

use \Yeti\Core\Model\Page;
use \Yeti\Main\Model\Project;

use \Yeti\Main\Building\Queue;

use \Able\IO\Path;
use \Able\IO\Directory;

class YetiProjectDeploy extends ACommand {

	/**
	 * @var bool
	 */
	protected static $scopable = true;

	/**
	 * @var string
	 */
	protected static $prefix = 'deploy';

	/**
	 * @var string
	 */
	protected $signature = 'yeti:project:deploy {--rebuild} {--silent}';

	/**
	 * @throws \Throwable
	 */
	public function handle(): void {
		$count = 0;

		if ($this->option('rebuild')){
			$this->call('yeti:data:build', ['scope' => 'writersperhour', '--force' => true,
				'--silent' => $this->option('silent'), '--pid' => $this->option('pid'), '--keep' => true]);

			$this->call('yeti:project:build', ['scope' => 'writersperhour', '--force' => true,
				'--silent' => $this->option('silent'), '--pid' => $this->option('pid'), '--keep' => true]);
		}

		$count += $this->deployPages();
		$count += $this->deployData();
		$count += $this->deployResources();
		$count += $this->deployFiles();

		if (!$this->option('silent')){
			$this->info(sprintf('Total: %s', $count));
		}
	}

	/**
	 * @throws \Exception
	 * @return int
	 */
	protected final function deployPages(): int {
		$Destination = Path::create(App::scope()->option('deploy_path'),
			'/pages/')->try(function(Path $Path){
				throw new \Exception(sprintf('Destination is not a directory or not writable: %s!', $Path));
			}, Path::TIF_NOT_DIRECTORY | Path::TIF_NOT_WRITABLE)->forceDirectory();

		$Destination->clear();
		$Target = Path::create(Config::get('building.destination'),
			App::scope()->name, 'pages')->forceDirectory();

		$count = 0;
		foreach ($Target->list() as $Path) {
			if (!$Path->isDot() && $Path->isDirectory()) {
				$this->saveProcessInfo(ceil((++$count * 100 / $Target->count()) / 4));
				$Path->toDirectory()->copy($Destination->toPath());

				usleep(1000);
				if (!$this->option('silent')) {
					$this->info(sprintf('[%1$03d%% Done] Copying page %2$s',
						$this->getProcessInfo(), $Path->getEnding()));
				}
			}
		}

		return $count;
	}


	/**
	 * @throws \Exception
	 * @return int
	 */
	protected final function deployData(): int {
		$Destination = Path::create(App::scope()->option('deploy_path'),
			'/data/')->try(function(Path $Path){
				throw new \Exception(sprintf('Destination is not a directory or not writable: %s!', $Path));
			}, Path::TIF_NOT_DIRECTORY | Path::TIF_NOT_WRITABLE)->forceDirectory();

		$Destination->clear();
		$Target = Path::create(Config::get('building.destination'),
			App::scope()->name, 'data')->forceDirectory();

		$count = 0;
		foreach ($Target->list() as $Path) {
			if (!$Path->isDot() && $Path->isDirectory()) {
				$this->saveProcessInfo(ceil((++$count * 100 / $Target->count()) / 2));
				$Path->toDirectory()->copy($Destination->toPath());

				usleep(1000);
				if (!$this->option('silent')) {
					$this->info(sprintf('[%1$03d%% Done] Copying data folder %2$s',
						$this->getProcessInfo(), $Path->getEnding()));
				}
			}
		}

		return $count;
	}

	/**
	 * @return int
	 * @throws \Exception
	 */
	protected final function deployResources(): int {
		$Destination = Path::create(App::scope()->option('deploy_path'),
			'/public/')->try(function(){
				throw new \Exception('Destination is not a directory or not writable!');
		}, Path::TIF_NOT_DIRECTORY | Path::TIF_NOT_WRITABLE)->forceDirectory();

		$Target = Path::create(Config::get('building.destination'),
			App::scope()->name, 'resources')->forceDirectory();

		$count = 0;
		foreach ($Target->list() as $Path){
			$this->saveProcessInfo((ceil(++$count * 100 / $Target->count()) / 1.23));

			if (!$Path->isDot()
				&& !in_array($Path->getEnding(), ['index.php'])){

				if ($Path->getEnding() == 'styles') {
					$this->minimizeCss($Path->toDirectory(), $Destination);
				}elseif ($Path->getEnding() == 'scripts') {
					$this->minimizeJs($Path->toDirectory(), $Destination);
				} else {
					$Path->toNode()->copy($Destination, true);
				}

				if (!$this->option('silent')) {
					$this->info(sprintf('[%1$03d%% Done] Copying resource ./%2$s',
						$this->getProcessInfo(), $Path->getEnding()));
				}
			}
		}

		return $count;
	}

	/**
	 * @param Directory $Source
	 * @param Directory $Destination
	 * @throws \Exception
	 */
	protected final function minimizeCss(Directory $Source, Directory $Destination): void {
		$Minimifier = new CSS();

		foreach ($Source->toPath()->append('.minify')->forceFile()->toReader()->read() as $file) {
			$Minimifier->add($Source->toPath()->append($file)->toFile()->toString());
		}

		$Minimifier->minify($Destination->toPath()
			->forceDirectory()->toPath()->append('main.min.css')->forceFile()->toString());
	}

	/**
	 * @param Directory $Source
	 * @param Directory $Destination
	 * @throws \Exception
	 */
	protected final function minimizeJs(Directory $Source, Directory $Destination): void {
		$Minimifier = new JS();

		foreach ($Source->toPath()->append('.minify')->forceFile()->toReader()->read() as $file) {
			$Minimifier->add($Source->toPath()->append($file)->toFile()->toString());
		}

		$Minimifier->minify($Destination->toPath()
			->forceDirectory()->toPath()->append('main.min.js')->forceFile()->toString());
	}

	/**
	 * @return int
	 * @throws \Exception
	 */
	public final function deployFiles(): int {
		$Destination = Path::create(App::scope()->option('deploy_path'),
			'/public/')->try(function(){
				throw new \Exception('Destination is not a directory or not writable!');
		}, Path::TIF_NOT_DIRECTORY | Path::TIF_NOT_WRITABLE)->forceDirectory();

		$count = 0;
		$Destination->toPath()->append('robot.txt')->forceFile()->rewrite(preg_replace('/\s*sitemap:.*\s*/im', null,
			App::scope()->option('robots')) . PHP_EOL . 'Sitemap: ' . App::scope()->url . "/sitemap.xml" . PHP_EOL);

		$count++;
		return $count;
	}
}
