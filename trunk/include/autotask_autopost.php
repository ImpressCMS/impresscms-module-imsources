<?php

if (!function_exists('icms_getModuleConfig')) {
	die('Error: This file can\'t be accesed directly!');
}

$module_handler = &xoops_gethandler('module');
$dirname = basename(dirname(dirname(__FILE__)));
$GLOBALS['icmsModule'] =& $module_handler->getByDirname($dirname);

$ctime = intval(ini_get('max_execution_time'));

require_once ICMS_ROOT_PATH . '/class/template.php';

$relations_handler = &icms_getmodulehandler('relation', 'imsources');
$exporters_handler = &icms_getmodulehandler('exporter', 'imsources');
$user_handler = &xoops_gethandler('user');
$exporters = &$exporters_handler->getExporters();
foreach($exporters as $exporter) {
	echo sprintf('Importing %s...', $exporter->getVar('name')) . "<br />\n";
	$data = &$exporter->getData();	
	if (!$data || (count($data) < 1))  continue;
	$file = ICMS_CACHE_PATH . '/imsources-exporter-' . $exporter->getVar('exporter_id') . '.html' ;	
	file_put_contents($file, $exporter->getVar('template'));	
	$object_handler = &$exporter->getSelectedObjectHandler();
	$title_field = $exporter->getFieldName('title');
	$author_field = $exporter->getFieldName('author');
	$content_field = $exporter->getFieldName('content');
	$date_field = $exporter->getFieldName('date');
	$author_id = (int)$exporter->getVar('post_user');	
	$GLOBALS['icmsUser'] = &$user_handler->get($author_id);
	foreach ($data as $item) {
			$tpl = new XoopsTpl();
			$tpl->assign('item', $item->toArray());
			$content = $tpl->fetch($file);						
			$object = &$object_handler->create();
			$title = $item->getVar('title');			
			if ($title == '') {
				$title = $exporter->getDisplayTitle();				
			} 
			$object->setVar($title_field,  $title);
			$object->setVar($author_field,  $author_id);
			$object->setVar($content_field,  $content);
			$object->setVar($date_field,  time());
			$object->store(true);
			$relations_handler->link($exporter, $item);
			set_time_limit($ctime += 30);
			ini_set('max_execution_time', $ctime);
			sleep(5);			
			//var_dump(array($item->getVar('data_id'), $title));
	}
	unlink($file);
}

/*$types = &$types_handler->getObjects();
foreach($types as $type) {
	echo sprintf('Importing %s...', $type->getVar('name')) . "\n";
	$type->exec();
	set_time_limit($ctime += 5);
	ini_set('max_execution_time', $ctime);
}*/