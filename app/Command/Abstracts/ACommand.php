<?php
namespace Yeti\Main\Command\Abstracts;

use \Illuminate\Console\Command;
use \Illuminate\Console\Parser;

use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Config;

use \Symfony\Component\Console\Input\ArgvInput;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputDefinition;
use \Symfony\Component\Console\Output\OutputInterface;

use \Able\IO\Path;
use \Able\IO\File;

use \Able\Helpers\Fs;
use \Able\Helpers\Src;
use \Able\Helpers\Str;

use \Able\Prototypes\TTraitable;

use \Yeti\Main\Model\Project;

abstract class ACommand extends Command {
	use TTraitable;

	/**
	 * @var bool
	 */
	protected static $scopable = false;

	/**
	 * @var string
	 */
	protected static $prefix = 'command';

	/**
	 * @throws \Exception
	 */
	private function detectScope(): void {
		if (static::$scopable && !defined('__SCOPE__')) {
			try {
				define('__SCOPE__', Project::where('name', '=',
					$this->argument('scope'))->first()->id);
			} catch (\Exception $Exception) {
				throw new \Exception('Invalid scope!');
			}
		}
	}

	/**
	 * APidable constructor.
	 * @throws \Exception
	 */
	public function __construct() {
		parent::__construct();

		if (static::$scopable && !$this->getDefinition()->hasArgument('scope')) {
			$this->getDefinition()->addArgument(new InputArgument('scope',
				InputArgument::REQUIRED));
		}

		if (!$this->getDefinition()->hasOption('force')){
			$this->getDefinition()->addOption(new InputOption('force'));
		}

		if (!$this->getDefinition()->hasOption('keep')){
			$this->getDefinition()->addOption(new InputOption('keep'));
		}

		if (!$this->getDefinition()->hasOption('pid')){
			$this->getDefinition()->addOption(new InputOption('pid', null, InputOption::VALUE_OPTIONAL));
		}
	}

	/**
	 * @var File
	 */
	private $PidFile = null;

	/**
	 * @return File
	 * @throws \Exception
	 */
	protected final function getPidFile(): File {
		if (is_null($this->PidFile)){
			throw new \Exception('Process is not running!');
		}

		return $this->PidFile;
	}

	/**
	 * @throws \Exception
	 */
	private function prepareProcessFile(): void {
		$this->PidFile = Path::create(Fs::normalize($this->option('pid')
			?? md5(get_class($this)) . '.pid', base_path()))->try(function(Path $Path){
				$Path->prepend(base_path('storage'));
		}, Path::TIF_NOT_ABSOLUTE)->try(function(){ if (!$this->option('force')) {
			throw new \Exception('Process is already running!');
		}}, Path::TIF_EXIST)->forceFile();

		$this->PidFile->purge();
	}

	/**
	 * @throws \Exception
	 */
	private function removeProcessFile(){
		if (!$this->option('keep')) {
			$this->getPidFile()->remove();
		}

		$this->PidFile = null;
	}

	/**
	 * @param string $info
	 * @throws \Exception
	 */
	protected final function saveProcessInfo(string $info): void {
		$this->getPidFile()->rewrite(Str::join(';', static::$prefix, $info));
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	protected final function getProcessInfo(): string {
		return preg_replace('/^[^;]+;/', '', $this->getPidFile()->getContent());
	}

	/**
	 * @param InputInterface $Input
	 * @param OutputInterface $Output
	 * @return int
	 * @throws \Exception
	 */
	protected function execute(InputInterface $Input, OutputInterface $Output): int {
		try {
			$this->detectScope();
			$this->prepareProcessFile();

			try {
				$this->handle();

				return 0;
			} finally {
				$this->removeProcessFile();
			}

		}catch (\Throwable $Exception){
			$this->exception($Exception);

			return 1;
		}
	}

	/**
	 * @param \Throwable $Exception
	 * @return void
	 */
	public final function exception(\Throwable $Exception): void {
		if (Config::get('app.debug', false)){
			$this->error(sprintf("%s in %s on %d",
				$Exception->getMessage(), $Exception->getFile(), $Exception->getLine()));

		} else {
			$this->error($Exception->getMessage());
		}
	}

	abstract protected function handle(): void;
}
