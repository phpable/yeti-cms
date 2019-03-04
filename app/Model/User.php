<?php
namespace Yeti\Main\Model;

use \Able\Helpers\Arr;
use \Able\Helpers\Jsn;

use \Yeti\Main\Model\Abstracts\AModel;

use \Illuminate\Auth\Authenticatable;
use \Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends AModel implements AuthenticatableContract {
	use Authenticatable;

	/**
	 * @var string
	 */
	protected $table = 'yeti_main_users';

	/**
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * @var array
	 */
	protected $appends = ['uid'/*, 'path'*/];

	/**
	 * @var array
	 */
	protected $hidden = ['password'];

	/**
	 * @return string
	 */
	public final function getUidAttribute(): string {
		return sprintf('%1$05d', $this->id);
	}

}
