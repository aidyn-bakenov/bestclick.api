<?php

use Bitrix\Main\Loader;

$moduleId = 'bestclick.api';

Loader::registerAutoLoadClasses($moduleId, [
	'Bestclick\Routing\Router' => 'lib/Bestclick/Routing/Router.php',
	'Bestclick\Routing\RouteItem' => 'lib/Bestclick/Routing/RouteItem.php',
]);