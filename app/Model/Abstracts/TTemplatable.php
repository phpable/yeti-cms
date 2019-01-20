<?php
namespace Yeti\Main\Model\Abstracts;

use \Illuminate\Database\Eloquent\Relations\MorphMany;
use \Illuminate\Database\Eloquent\Model;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Core\Model\Template;

trait TTemplatable {

	/**
	 * @return MorphMany
	 */
	public final function templates(): MorphMany {
		return $this->morphMany(Template::class, 'owner');
	}

	/**
	 * Assign observable events.
	 */
	public static final function boot() {
		parent::boot();

		/**
		 * Remove all sources too.
		 */
		self::deleting(function(AModel $Page){
			foreach($Page->templates as $Template){
				$Template->delete();
			}
		});
	}

}
