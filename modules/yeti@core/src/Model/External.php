<?php
namespace Yeti\Core\Model;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TProject;

use \Illuminate\Database\Eloquent\Relations\BelongsTo;

use \Yeti\Core\Model\Layout;

class External extends AModel {
	use TProject;

	/**
	 * @var string
	 */
	protected $table = 'yeti_main_externals';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = ['type', 'link'];

	/**
	 * @return BelongsTo
	 */
	public function layout(){
		return $this->belongsTo(Layout::class);
	}
}
