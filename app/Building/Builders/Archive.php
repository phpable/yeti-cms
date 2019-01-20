<?php
namespace Yeti\Main\Building\Builders;

use \Yeti\Blog\Model\Post;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Template;
use \Yeti\Main\Model\Abstracts\AModel;

class Archive extends Standard {

	/**
	 * @param Page $Target
	 * @param Template $View
	 * @param array $Params
	 * @return string
	 * @throws \Throwable
	 */
	public function make(Page $Target, Template $View, array $Params){
		$Items = Post::all();
		foreach (array_unique($Items->lists('year')->toArray()) as $year) {
			foreach (array_unique($Items->where('year', $year)->lists('month')->toArray()) as $month) {
				$path = $this->getTargetPath($Target) . '/' . ($url = $year . ($month = strtolower($month)));
				if (file_exists($path)) {
					\File::deleteDirectory($path);
				}
				mkdir($path, 0777, true);
				(new Paginate(['target_path' => $path]))->build($Target, [
					'items' => $Items->where('year', $year)->where('month', $month),
						'scope' => ['year' => $year, 'month' => date('F', mktime(0, 0, 0, $month, 10))]], true);
			}
		}

		return "<?php\n" . '$file = __DIR__ . "/{$year}" . strtolower(trim($month)) . "/view.php";' . "\n"
			. 'if (file_exists($file) && is_file($file)) {' . "\n\t" . 'include ($file);' . "\n"
				. "} else {\n\tApp::abort(404);\n}" . "\n?>\n";

	}

}
