<?php
require __DIR__ . '/../bootstrap/autoload.php';

error_reporting(~0);
ini_set('display_errors', 1);

$App = require_once __DIR__ . '/../bootstrap/app.php';

$Kernel = $App->make(Illuminate\Contracts\Http\Kernel::class);

$Request = \Illuminate\Http\Request::capture();

$Response = $Kernel->handle($Request);

$Response->send();

$Kernel->terminate($Request, $Response);
