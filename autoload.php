<?php
$path = dirname(__FILE__);

require_once($path.'/lib/sp/loader.php');
require_once($path.'/domstorlib.php');

$loader = new SP_Loader();
$loader->registerPrefix('SP', $path.'/lib');
$loader->registerPrefix('Domstor', $path.'/lib');
$loader->registerPrefix('Doctrine', $path.'/lib');
$loader->register();
