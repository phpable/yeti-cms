<?php
namespace Yeti\Main\Boot;

use \Able\IO\Path;
use \Able\IO\File;

use \Illuminate\Foundation\Application;
use \Illuminate\Database\Eloquent\Collection;

use \Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Session;

use \Yeti\Main\Model\User;
use \Yeti\Main\Model\Module;
use \Yeti\Main\Model\Project;

use \Yeti\Main\Model\Scope\ProjectScope;

use \Yeti\Main\Building\Exports\Exporter;

class Runtime extends Application {

	/**
	 * @var null
	 */
	private $Exporter = null;

	/**
	 * @return Exporter
	 * @throws \Exception
	 */
	public final function exporter(): Exporter {
		if (is_null($this->Exporter)) {
			$this->Exporter = new Exporter(include  Path::create(temporary_path('export.php'))->toFile());
		}

		return $this->Exporter;
	}

	/**
	 * @return Project
	 * @throws \Exception
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

	/**
	 * @param User $User
	 * @return Workspace
	 * @throws \Exception
	 */
	public final function getWorkspace(User $User): Workspace {
		return new Workspace(new Path(user_path($User)));
	}
}
