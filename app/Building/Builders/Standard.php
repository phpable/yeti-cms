<?php
namespace Yeti\Main\Building\Builders;

use \Yeti\Core\Model\Layout;
use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Template;
use \Yeti\Core\Model\Snippet;
use \Yeti\Core\Model\Constant;

use \Yeti\Main\Model\Project;
use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\ITemplatable;

use \Yeti\Main\Building\Utilities\Collector;
use \Yeti\Main\Building\Builders\Abstractions\ABuilder;

use \Yeti\Main\Building\Storages\Structures\SMeta;
use \Yeti\Main\Building\Storages\Structures\SExternal;

use \Able\Helpers\Arr;
use \Able\Helpers\Jsn;

use \Able\IO\Path;
use \Able\IO\File;
use \Able\IO\Writer;
use \Able\IO\Directory;
use \Able\IO\ReadingBuffer;
use \Able\IO\WritingBuffer;

use \Able\Sabre\Standard\Delegate;

class Standard extends ABuilder {

	/**
	 * @param Directory $Directory
	 * @throws \Throwable
	 */
	public function build(Directory $Directory): void {
		$Directory = (new Path($Directory, $this->Page->name))->forceDirectory();
		$Directory->clear();

		$this->proceed($this->Page, $Directory);
	}

	/**
	 * @param Page $Page
	 * @param Directory $Terget
	 * @param array $Arguments
	 * @param array $Overrides
	 * @throws \Exception
	 */
	public final function proceed(Page $Page, Directory $Terget, array $Arguments = [], array $Overrides = []){
		$Terget->toPath()->append('page.json')->forceFile()
			->rewrite(Jsn::encode(array_merge($this->Page->config, ['url' => $this->Page->url], $Overrides)));

		$View = $Terget->toPath()->append('view.php')->forceFile();

		Collector::stack('page', $this->Page);
		if ($this->Page->builder == 'standard') {
			Collector::externals()->addExternal(new SExternal(SExternal::ET_CANONICAL, $this->Page->absolute_url));
		}

		Collector::stack('layout', $this->Page->layout);
		Collector::externals()->addExternals($this->Page->layout->retrieveExternalsList());

		Collector::metas()->addMetas($this->Page->layout->retrieveMetasList());
		Collector::metas()->addMeta(new SMeta(SMeta::MT_NAME, 'description', $this->Page->description));

		$View->toWriter()->write(Delegate::compile(Collector::restack('layout')->combine($this->Page->getLayoutPartialName())),
			Writer::WM_SKIP_EMPTY | Writer::WM_SKIP_INDENT);

		Collector::flush();
	}

}
