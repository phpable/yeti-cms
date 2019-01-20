<?php
namespace Yeti\Main\Building\Builders;

use \Able\IO\Path;
use \Able\IO\Directory;

use \Able\Helpers\Str;
use \Able\Helpers\Arr;

use \Yeti\Blog\Model\Post;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Template;

use \Yeti\Main\Building\Utilities\Collector;
use \Yeti\Main\Model\Project;
use \Yeti\Main\Model\Abstracts\AModel;

use \Illuminate\Database\Eloquent\Collection;

class Populate extends Standard {

	/**
	 * @var string
	 */
	private $provider = null;

	/**
	 * @return Collection
	 */
	public final function loadExternalData(){
		return Post::all();
	}

	/**
	 * @param Page $Page
	 * @param array $Arguments
	 * @throws \Exception
	 */
	public final function __construct(Page $Page, array $Arguments = []) {
		if (!isset($Arguments['provider'])) {
			throw new \Exception('Invalid arguments!');
		}

		if ($Arguments['provider'] !== 'blog') {
			throw new \Exception('Invalid provider!');
		}

		parent::__construct($Page);
	}

	/**
	 * @param Directory $Directory
	 * @throws \Throwable
	 */
	public final function build(Directory $Directory): void {
		foreach ($this->loadExternalData() as $Post){
			$Target = (new Path($Directory, Str::join('-',
				$this->Page->name, 'populate', sprintf('%1$04d', $Post->id))))->forceDirectory();

			$Target->clear();

			Collector::registerPartials('post', $this->filterData($Post->toArray()));
			Collector::registerPartials('topic', $this->filterData($Post->topic->toArray()));

			$this->proceed($this->Page, $Target, ['Post' => $Post], array_merge(Arr::only($Post->toArray(),
				'title', 'description'), ['url' => preg_replace('/\{ *\$url\ *}/', $Post->url, $this->Page->url)]));
		}
	}

	/**
	 * @param array $Values
	 * @return array
	 */
	public final function filterData(array $Values): array {
		foreach ($Values as $key => $value){
			if (in_array($key, ['created_at', 'updated_at', 'published_at'])){
				$Values[$key] = date('d M Y', strtotime($value));
			}
		}

		return $Values;
	}
}
