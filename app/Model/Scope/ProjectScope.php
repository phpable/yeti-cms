<?php
namespace Yeti\Main\Model\Scope;

use \Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Session;

use \Illuminate\Database\Eloquent\ScopeInterface;
use \Illuminate\Database\Eloquent\Builder;

use \Illuminate\Database\Eloquent\Model;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Project;

use \Yeti\Main\Exception\InvalidScopeException;

class ProjectScope implements ScopeInterface {

	/**
	 * @return Project
	 * @throws InvalidScopeException
	 */
	public static final function detectActiveScope(): Project{
		if (php_sapi_name() == 'cli'){
			if (!defined('__SCOPE__')) {
				throw new InvalidScopeException();
			}

			/**
			 * Here is the good way to retrieve
			 * temporary project scope for the cli runtime.
			 */
			return Project::findOrFail(__SCOPE__);
		}

		if (!defined('__SCOPE__') && !Session::has('__SCOPE__')) {
			throw new InvalidScopeException();
		}

		/**
		 * The problem is that the project scope can change in the real time so the best decision
		 * would be just take it from a database every time.
		 *
		 * As here is the CMS code only and no heighload troubles are expected
		 * so this decision looks acceptable.
		 *
		 * But maybe we will decide to cache it in the future.
		 */
		return Project::findOrFail(defined('__SCOPE__')
			? __SCOPE__ : (int)Session::get('__SCOPE__'));
	}

	/**
	 * @param Builder $Builder
	 * @param Model $Model
	 * @throws \Exception
	 */
	public function apply(Builder $Builder, Model $Model) {
		$Builder->where($Model->getTable() . '.project_id',
			'=', self::detectActiveScope()->id);
	}

	/**
	 * @param Builder $Builder
	 * @param Model $Model
	 * @throws \Exception
	 */
	public function remove(Builder $Builder, Model $Model) {
		throw new \Exception('Removing of the project scope is forbidden!');
	}
}



