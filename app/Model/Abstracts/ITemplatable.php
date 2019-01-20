<?php
namespace Yeti\Main\Model\Abstracts;

use \Illuminate\Database\Eloquent\Relations\MorphMany;

interface ITemplatable {

	/**
	 * @return MorphMany
	 */
	public function templates(): MorphMany;
}
