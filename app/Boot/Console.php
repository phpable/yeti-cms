<?php
namespace Yeti\Main\Boot;

use \Illuminate\Foundation\Console\Kernel;

use \Yeti\Main\Command\YetiExport;
use \Yeti\Main\Command\YetiImport;
use \Yeti\Main\Command\YetiProjectDeploy;
use \Yeti\Main\Command\YetiModulesUpdate;
use \Yeti\Main\Command\YetiAdminList;
use \Yeti\Main\Command\YetiAdminCreate;
use \Yeti\Main\Command\YetiProjectCreate;
use \Yeti\Main\Command\YetiProjectConfig;

use \Yeti\Main\Command\YetiProjectBuild;

use \Yeti\Main\Command\YetiResourcesCheck;

use \Yeti\Main\Command\LaravelConsoleMake;
use \Yeti\Main\Command\LaravelCommandMake;
use \Yeti\Main\Command\LaravelMacrosMake;
use \Yeti\Main\Command\LaravelControllerMake;
use \Yeti\Main\Command\LaravelMiddlewareMake;
use \Yeti\Main\Command\LaravelModelMake;

class Console extends Kernel {

	/**
	 * The Artisan commands provided by your application.
	 * @var array
	 */
	protected $commands = [
		YetiResourcesCheck::class,

		YetiExport::class,
		YetiImport::class,
		YetiProjectBuild::class,
		YetiProjectDeploy::class,
		YetiModulesUpdate::class,
		YetiAdminList::class,
		YetiAdminCreate::class,
		YetiProjectCreate::class,
		YetiProjectConfig::class,

		LaravelConsoleMake::class,
		LaravelCommandMake::class,
		LaravelMacrosMake::class,
		LaravelMiddlewareMake::class,
		LaravelModelMake::class,
	];

}
