<?php

if (!function_exists('icms_getModuleConfig')) {
	die('Error: This file can\'t be accesed directly!');
}

$module_handler = &xoops_gethandler('module');
$dirname = basename(dirname(dirname(__FILE__)));
$GLOBALS['icmsModule'] =& $module_handler->getByDirname($dirname);

$ctime = intval(ini_get('max_execution_time'));

$types_handler = &icms_getmodulehandler('type', 'imsources');
$types = &$types_handler->getObjects();
foreach($types as $type) {
	echo sprintf('Importing %s...', $type->getVar('name')) . "\n";
	$type->exec();	
	set_time_limit($ctime += 20);
	ini_set('max_execution_time', $ctime);
	sleep(5);
}