<?php
namespace Yeti\Main\Controller;

use \Illuminate\Support\Facades\Input;

use \Yeti\Main\Model\Abstracts\AModel;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Layout;
use \Yeti\Core\Model\Template;

use \Yeti\Main\Controller\Abstracts\AController;
use \Yeti\Main\Exception\RetrievableException;

use \Able\Helpers\Arr;
use \Able\Helpers\Arg;

class EditorController extends AController {

	/**
	 * @return array
	 * @throws \Exception
	 */
	public final function rename() {
		if (!Arr::has(Input::all(), 'id', 'name')){
			throw new RetrievableException('Invalid request!');
		}

		return ['error' => false, 'name' => Template::findOrFail(Input::get('id'))
			->update(['name' => Input::get('name')])->name];
	}

	/**
	 * @return array
	 * @throws RetrievableException
	 * @throws \Throwable
	 */
	public final function create() {
		if (!Arr::has(Input::all(), 'pid', 'type', 'owner')){
			throw new RetrievableException('Invalid request!');
		}

		if (!class_exists($class = AModel::fromType(Input::get('owner')))){
			throw new RetrievableException('Invalid request!');
		}

		if (!is_subclass_of($class, AModel::class)){
			throw new RetrievableException('Invalid request!');
		}

		if (is_null($Owner = $class::find(Input::get('pid')))){
			throw new RetrievableException('Invalid request!');
		}

		$Template = $Owner->templates()->create([
			'name' => Template::generate($Owner, Input::get('type')),
			'type' => Input::get('type')
		]);

		return ['error' => false, 'id' => $Template->id,
			'__TAB__' => view('ide.tabbtn', ['name' => $Template->name,
				'type' => $Template->type, 'id' => $Template->id])->render(),
			'__EDITOR__' => view('ide.editor', ['name' => 'sources['. $Template->id . ']',
				'type' => $Template->type, 'id' => $Template->id])->render()
		];

	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public final function delete() {
		if (!Arr::has(Input::all(), 'id')){
			throw new RetrievableException('Invalid request!');
		}

		Template::findOrFail(Input::get('id'))->delete();
		return ['error' => false, 'id' => Input::get('id')];
	}
}
