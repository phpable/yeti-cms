<?php
namespace Yeti\Main\Building;

use Exception;
use \Illuminate\Support\Facades\App;

use \Yeti\Core\Model\Layout;
use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Template;
use \Yeti\Core\Model\Snippet;
use \Yeti\Core\Model\Constant;

use \Yeti\Main\Model\Project;
use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\ITemplatable;

use \Yeti\Main\Building\Utilities\Collector;

use \Yeti\Main\Building\Storages\Structures\SMeta;
use \Yeti\Main\Building\Storages\Structures\SExternal;

use \Able\Helpers\Arr;
use \Able\Helpers\Jsn;
use \Able\Helpers\Str;

use \Able\IO\Path;
use \Able\IO\File;
use \Able\IO\Writer;
use \Able\IO\Directory;
use \Able\IO\ReadingBuffer;
use \Able\IO\WritingBuffer;

use \Able\Reglib\Regex;

use \Able\Sabre\Standard\Delegate;

class Builder {

	/**
	 * @var Directory
	 */
	private $Destination = null;

	/**
	 * @var string
	 */
	private $hash = null;

	/**
	 * @param Directory $Distination
	 * @throws Exception
	 */
	public final function __construct(Directory $Distination) {
		$this->Destination = $Distination;
		$this->hash = md5(implode('|', [__FILE__, $Distination->toString(), time()]));
	}

	/**
	 * @param Page $Page
	 * @throws \Throwable
	 */
	public function build(Page $Page): void {
		if (!preg_match('/^[A-Za-z]+[A-Za-z_-]/', $Page->name)){
			throw new Exception('Page name is invalid or empty!');
		}

		if ($Page->is_hidden) {
			throw new Exception('Page is hidden!');
		}

		if (is_null($Page->layout)) {
			throw new Exception('Undefined layout!');
		}

		$Directory = (new Path($this->Destination, $Page->name))->forceDirectory();
		$Directory->clear();

		$this->proceed($Page, $Directory);
	}

	/**
	 * @param Page $Page
	 * @param Directory $Terget
	 * @param array $Arguments
	 * @param array $Overrides
	 * @throws Exception
	 */
	public final function proceed(Page $Page, Directory $Terget, array $Arguments = [], array $Overrides = []){
		$Terget->toPath()->append('page.json')->forceFile()
			->rewrite(Jsn::encode(array_merge($Page->config, [
				'url' => $Page->url,
				'hash' => $Page->hash,

				'__build_hash' => $this->hash,
			], $Overrides)));

		$View = $Terget->toPath()
			->append('view.php')->forceFile();

		Collector::stack('page', $Page);
		if ($Page->builder == 'standard') {
			Collector::externals()->addExternal(new SExternal(SExternal::ET_CANONICAL, $Page->absolute_url));
		}

		Collector::stack('layout', $Page->layout);

		Collector::externals()->addExternals($Page->layout->retrieveExternalsList());
		Collector::metas()->addMetas($Page->layout->retrieveMetasList());

		$View->toWriter()->write(Delegate::compile(Collector::restack('layout')->combine($Page->getLayoutPartialName())),
			Writer::WM_SKIP_EMPTY | Writer::WM_SKIP_INDENT);

		Collector::flush();
	}

}
