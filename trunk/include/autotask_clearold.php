<?php
if (!function_exists('icms_getModuleConfig')) {
	die('Error: This file can\'t be accesed directly!');
}

$module_handler = &xoops_gethandler('module');
$dirname = basename(dirname(dirname(__FILE__)));
$GLOBALS['icmsModule'] =& $module_handler->getByDirname($dirname);

$data_handler = &icms_getmodulehandler('data', 'imsources');
require_once ICMS_ROOT_PATH . '/class/criteria.php';
$cr = new CriteriaCompo();
$cr->add(new Criteria('date', strtotime('-1 week'), '<'));
$data = &$data_handler->getObjects($cr);
foreach ($data as $item) {
	if ($item->delete()) {
		echo sprintf("%s deleted.\n<br />", $item->title);
	} else {
		echo sprintf("Was not %s deleted.\n<br />", $item->title);
	}
}