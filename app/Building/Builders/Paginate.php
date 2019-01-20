<?php
namespace Yeti\Main\Building\Builders;

use \Able\Helpers\Arr;

use \Yeti\Blog\Model\Post;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Template;
use \Yeti\Main\Model\Abstracts\AModel;

class Paginate extends  Standard {

	/**
	 * @param Page $Page
	 * @param Template $View
	 * @param array $Params
	 * @return string
	 */
	public function make(Page $Page, Template $View, array $Params){
		$Items = array_key_exists('items', array_change_key_case($Params, CASE_LOWER))
			? $Params['items'] : Post::where('url', '<>', "")->orderBy('created_at', 'desc')->get();

		for($page = 1; $page <= ceil(count($Items) / 5); $page++){
			$path = $this->getTargetPath($Page) . 'page' . $page;
			if (file_exists($path)){
				\File::deleteDirectory($path);
			}
			mkdir($path, 0777, true);

			file_put_contents($path . '/view.php', (new Parser())->inject('Items', $Items
				->slice(($page - 1) * 5, 5)->toArray())->inject('pages', ceil(count($Items) / 5))
					->inject('page', $page)->load(Arr::get($Params, 'scope', []))->parse($View->source));
		}

		return "<?php\n" . '$file = __DIR__ . "/' . '/page" . (isset($_GET["page"]) ? $_GET["page"] : 1) . "/view.php";' . "\n"
			.  'if (file_exists($file) && is_file($file)) {' . "\n\t" . 'echo file_get_contents($file);' . "\n"
				. "} else {\n\tApp::abort(404);\n}" .  "\n?>\n";

	}

}
