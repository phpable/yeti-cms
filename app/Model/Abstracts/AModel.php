<?php
namespace Yeti\Main\Model\Abstracts;

use \Able\Helpers\Arr;
use \Able\Helpers\Src;

use \Illuminate\Database\Eloquent\Model;

abstract class AModel extends Model {

	/**
	 * @param array $Options
	 * @return AModel
	 */
	public final function save(array $Options = []) {
		parent::save($Options);

		/**
		 * It's a very useful feature to return the object
		 * instance here instead of a boolean value.
		 */
		return $this;
	}

	/**
	 * @return string
	 */
	public final function getType(){
		return implode('/', array_map(function($item){
			return Src::fcm($item, '-');
		}, explode('\\', get_class($this))));
	}

	/**
	 * @param string $type
	 * @return string
	 */
	public static final function fromType($type){
		return implode('\\', array_map(function($item){
			return Src::tcm($item, '-');
		}, explode('/', $type)));
	}

}
