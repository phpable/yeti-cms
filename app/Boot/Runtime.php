<?php
namespace Yeti\Main\Boot;

use \Illuminate\Foundation\Application;
use \Illuminate\Database\Eloquent\Collection;

use \Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Session;

use \Yeti\Main\Model\Module;
use \Yeti\Main\Model\Project;
use \Yeti\Main\Model\Scope\ProjectScope;

class Runtime extends Application {

	/**
	 * @return Project
	 */
	public final function scope(): Project {
		return ProjectScope::detectActiveScope();
	}

	/**
	 * @return bool
	 */
	public final function scopable(): bool {
		return Auth::check() && Session::has('__SCOPE__');
	}

	/**
	 * @return Collection
	 */
	public final function modules(): Collection {
		return Module::whereActive()->get();
	}
}
