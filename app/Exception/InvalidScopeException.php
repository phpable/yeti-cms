<?php
namespace Yeti\Main\Exception;

class InvalidScopeException extends \Exception {

	public final function __construct() {
		parent::__construct('Undefined project scope!');
	}
}


