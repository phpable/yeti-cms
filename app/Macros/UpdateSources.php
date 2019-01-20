<?php
namespace Yeti\Main\Macros;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Template;
use \Yeti\Main\Model\Abstracts\ITemplatable;

use \Illuminate\Contracts\Bus\SelfHandling;

class UpdateSources
	implements SelfHandling {

	/**
	 * @var null
	 */
	private $Owner = null;

	/**
	 * @var string[]
	 */
	private $Templates = [];

	/**
	 * @param ITemplatable $Owner
	 * @param string[] $Templates
	 */
	public function __construct(ITemplatable $Owner, array $Templates) {
		$this->Owner = $Owner;
		$this->Templates = $Templates;
	}

	public final function handle(){
		foreach ($this->Owner->templates as $Template){
			if (isset($this->Templates[$Template->id])){
				$hash = md5($this->Templates[$Template->id]);

				if ($Template->hash != $hash){
					$Template->source = $this->Templates[$Template->id];
					$Template->save();
				}
			}
		}
	}
}
