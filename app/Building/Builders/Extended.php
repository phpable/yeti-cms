<?php
namespace Yeti\Main\Building\Builders;

use \Able\IO\Path;
use \Able\IO\Directory;

use \Able\Helpers\Str;
use \Able\Helpers\Src;
use \Able\Helpers\Arr;

use \Yeti\Blog\Model\Author;
use \Yeti\Blog\Model\Post;
use \Yeti\Blog\Model\Topic;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Template;

use \Yeti\Main\Building\Utilities\Collector;
use \Yeti\Main\Model\Project;
use \Yeti\Main\Model\Abstracts\AModel;

use \Illuminate\Database\Eloquent\Collection;
use \Illuminate\Pagination\LengthAwarePaginator;

class Extended extends Standard {

	/**
	 * @var array
	 */
	private $Arguments = [];

	/**
	 * @param Page $Page
	 * @param array $Arguments
	 * @throws \Exception
	 */
	public final function __construct(Page $Page, array $Arguments = []) {
		$this->Arguments = array_change_key_case($Arguments, CASE_LOWER);
		parent::__construct($Page);
	}

	/**
	 * @param Directory $Directory
	 * @throws \Throwable
	 */
	public final function build(Directory $Directory): void {
		parent::build($Directory);

		$Directory = (new Path($Directory, $this->Page->name, 'data'))->forceDirectory();
		$Directory->clear();

		if (isset($this->Arguments['share'])){
			foreach ($this->Arguments['share'] as $Share){

				$Target = (new Path($Directory, $Share['as']))->forceDirectory();
				$Target->clear();

				if (!preg_match('/^[@#]/', $Share['by'])) {
					foreach ($this->loadSharedCollection($Share['item']) as $Item) {

						Path::create($Target, ($Share['by'] == 'id' ? $Item->{$Share['by']}
								: md5($Item->{$Share['by']})) . '.data')->forceFile()->rewrite(base64_encode(json_encode($Item)));
					}
				} else {
					$this->scale($Share['by'],
						$this->loadSharedCollection($Share['item']), $Target);
				}
			}
		}
	}

	/**
	 * @const int
	 */
	protected const PAGINATION_LIMIT = 14;

	/**
	 * @param string $type
	 * @param Collection $Collection
	 * @param Directory $Target
	 * @throws \Exception
	 */
	public final function scale(string $type, Collection $Collection, Directory $Target): void {
		switch (strtolower($type)) {
			case '@paginator':
				$Collection = array_reverse($Collection->toArray());

				for ($i = 0; $i < ceil(count($Collection) / self::PAGINATION_LIMIT); $i++) {
					Path::create($Target, 'page' . ($i + 1) . '.data')->forceFile()
						->rewrite(base64_encode(json_encode(array_slice($Collection, self::PAGINATION_LIMIT * $i, self::PAGINATION_LIMIT))));
				}

				break;
			case '#author':
				foreach ($this->loadSharedCollection('blog-author') as $Author){
					$Subset = array_reverse($Collection->where('author_id', $Author->id)->toArray());

					Path::create($Target, 'author' . md5($Author->id) . '.data')->forceFile()
						->rewrite(base64_encode(json_encode($Subset)));
				}

				break;
			case '#author-latest':
				foreach ($this->loadSharedCollection('blog-author') as $Author){
					$Subset = array_slice(array_reverse($Collection->where('author_id', $Author->id)
						->toArray()), 0, 4);

					Path::create($Target, 'author' . md5($Author->id) . '.data')->forceFile()
						->rewrite(base64_encode(json_encode($Subset)));
				}

				break;
			case '#topic':
				foreach ($this->loadSharedCollection('blog-topic') as $Topic){
					$Subset = array_reverse($Collection->where('topic_id', $Topic->id)->toArray());

					Path::create($Target, 'topic' . md5($Topic->url) . '.data')->forceFile()
						->rewrite(base64_encode(json_encode($Subset)));
				}

				break;
			default:
				throw new \Exception('Undefined type!');
		}
	}

	/**
	 * @param string $type
	 * @return Collection
	 * @throws \Exception
	 */
	public final function loadSharedCollection(string $type): Collection{
		switch (strtolower($type)){
			case 'blog-post':
				return Post::where('is_published', '=', 1)->get();
			case 'blog-topic':
				return Topic::all();
			case 'blog-author':
				return Author::all();
			default:
				throw new \Exception(sprintf('Undefined item type: %s!', $type));
		}
	}
}
