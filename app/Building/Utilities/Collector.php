<?php
namespace Yeti\Main\Building\Utilities;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\ITemplatable;

use \Yeti\Main\Building\Containers\Abstractions\AContainer;

use \Yeti\Main\Building\Storages\Metas;
use \Yeti\Main\Building\Storages\Externals;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Template;

use \Able\Prototypes\ISingleton;
use \Able\Prototypes\TSingleton;

use \Able\Helpers\Arr;
use \Able\Helpers\Str;
use \Able\Helpers\Src;

use \Able\IO\WritingBuffer;
use \Able\IO\ReadingBuffer;
use \Able\IO\ReadingContainer;

use \Able\IO\Abstractions\IReader;

use \Able\Sabre\Standard\Delegate;

class Collector {

	/**
	 * @var null
	 */
	private static $Externals = null;

	/**
	 * @return Externals
	 * @throws \Exception
	 */
	public final static function externals(): Externals {
		if (is_null(self::$Externals)){
			self::$Externals = new Externals();
		}

		return self::$Externals;
	}

	/**
	 * @var Metas
	 */
	private static $Metas = null;

	/**
	 * @return Metas
	 * @throws \Exception
	 */
	public final static function metas(): Metas {
		if (is_null(self::$Metas)){
			self::$Metas = new Metas();
		}

		return self::$Metas;
	}

	/**
	 * @var ITemplatable
	 */
	private static $Stacked = [];

	/**
	 * @param string $name
	 * @param ITemplatable $Model
	 * @throws \Exception
	 */
	public final static function stack(string $name, ITemplatable $Model){
		if (isset(self::$Resources[$name])) {
			throw new \Exception(sprintf('Stack with name "%s" already exists!', $name));
		}

		self::$Stacked[$name] = $Model;
	}

	/**
	 * @param string $name
	 * @return Collector
	 * @throws \Exception
	 */
	public final static function restack(string $name): Collector {
		if (!isset(self::$Stacked[$name])) {
			throw new \Exception(sprintf('Undefined stak name: %s', $name));
		}

		return (new self(self::$Stacked[$name]));
	}

	/**
	 * @var Template[]
	 */
	private static $History = [];

	/**
	 * @return void
	 */
	public static final function flush(): void {
		self::$Externals = null;
		self::$Metas = null;

		self::$Stacked = [];
		self::$History = [];
	}

	/**
	 * @var ITemplatable
	 */
	private $Model = null;

	/**
	 * Collector constructor.
	 * @param ITemplatable $Model
	 */
	public final function __construct(ITemplatable $Model) {
		$this->Model = $Model;

		$class = Src::lns(self::class, 2) . '\\Containers\\' . Src::rns(get_class($Model));
		if (class_exists($class)){
			$this->containerClass = $class;
		}

		return $this;
	}

	/**
	 * @var Template[]
	 */
	protected $Collection = [];

	/**
	 * @param string $name
	 * @param array $Params
	 * @return ReadingContainer
	 * @throws \Exception
	 */
	public final function combine(string $name, array $Params = []): ReadingContainer {
		$Container = AContainer::produce($this->Model);
		$Container->assignParams($Params);

		foreach ($this->Model->templates as $Template) {
			$Collection[] = $Template;
		}

		$Collection = array_filter($Collection, function (Template $Template) use ($name) {
			return $Template->type !== 'html' || $Template->name == $name;
		});

		foreach ($Collection as $Template) {
			if (!in_array($Template->getLOcation(), self::$History)) {
				if ($Template->type == 'html') {
					$Container->collect((new ReadingBuffer($Template)));
				} else {
					$Container->collect((new Container($Template))->setAlternateName($Template->type));
				}

				array_push(self::$History, $Template->getLocation());
			}
		}

		return $Container;
	}

}
