<?php
namespace Yeti\Core\Model;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TProject;

use \Illuminate\Database\Eloquent\Relations\BelongsTo;

use \Yeti\Core\Model\Layout;

use \Yeti\Main\Building\Structures\SMeta;

class Meta extends AModel {
	use TProject;

	/**
	 * @var string
	 */
	protected $table = 'yeti_main_metas';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = ['type', 'property', 'content'];

	/**
	 * @return BelongsTo
	 */
	public function layout(){
		return $this->belongsTo(Layout::class);
	}

}
