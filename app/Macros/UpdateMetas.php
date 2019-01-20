<?php
namespace Yeti\Main\Macros;

use \Yeti\Core\Model\Meta;
use \Yeti\Core\Model\Layout;

use \Yeti\Main\Model\Abstracts\ITemplatable;

use \Illuminate\Contracts\Bus\SelfHandling;

use \Able\Helpers\Arr;

class UpdateMetas implements SelfHandling {

	/**
	 * @var Layout
	 */
	private $Layout = null;

	/**
	 * @var array
	 */
	private $RawInput = [];

	/**
	 * UpdateMetas constructor.
	 * @param Layout $Layout
	 * @param array $RawInput
	 */
	public function __construct(Layout $Layout, array $RawInput) {
		$this->Layout = $Layout;
		$this->RawInput = Arr::only($RawInput, 'create', 'update', 'delete');
	}

	/**
	 * @throws \Exception
	 */
	public function handle() {
		foreach (array_keys($this->RawInput) as $action){
			if (!method_exists($this, $action)){
				throw new \Exception(sprintf('Invalid methos: %s!', $action));
			}

			$this->{$action}($this->RawInput[$action]);
		}
	}

	/**
	 * @param array $Queue
	 * @return void
	 */
	protected function create(array $Queue): void {
		foreach ($Queue as $Values){
			Meta::create(Arr::only($Values, 'type', 'property',
				'content'))->layout()->associate($this->Layout)->save();
		}
	}

	/**
	 * @param array $Queue
	 * @return void
	 */
	protected function update(array $Queue): void {
		foreach ($Queue as $id => $Values) {
			Meta::findOrFail($id)->update(Arr::only($Values,
				'type', 'property', 'content'));
		}
	}

	/**
	 * @param array $Queue
	 * @return void
	 */
	protected function delete(array $Queue): void {
		foreach ($Queue as $id) {
			Meta::findOrFail($id)->delete();
		}
	}
}
