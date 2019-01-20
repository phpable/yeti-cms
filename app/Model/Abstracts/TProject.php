<?php
namespace Yeti\Main\Model\Abstracts;

use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

use \Yeti\Main\Model\Scope\ProjectScope;

use \Yeti\Main\Model\Project;

trait TProject {

	/**
	 * Assign observable events.
	 */
	public static function bootTProject() {
		static::addGlobalScope(new ProjectScope());

		/**
		 * Link an item to the active project scope.
		 */
		static::saving(function (Model $Item) {
			$Item->project_id = ProjectScope::detectActiveScope()->id;
		});
	}

	/**
	 * @return BelongsTo
	 */
	public function project(){
		return $this->belongsTo(Project::class);
	}

}
