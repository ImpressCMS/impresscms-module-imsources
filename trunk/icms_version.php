<?php
/**
* imSources version infomation
*
* This file holds the configuration information of this module
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		MekDrop <i.know@mekdrop.name>
* @package		imsources
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

/**  General Information  */
$modversion = array(
  'name'=> _MI_IMSOURCES_MD_NAME,
  'version'=> 1.0,
  'description'=> _MI_IMSOURCES_MD_DESC,
  'author'=> "MekDrop",
  'credits'=> "",
  'help'=> "",
  'license'=> "GNU General Public License (GPL)",
  'official'=> 0,
  'dirname'=> basename( dirname( __FILE__ ) ),

/**  Images information  */
  'iconsmall'=> "images/icon_small.png",
  'iconbig'=> "images/icon_big.png",
  'image'=> "images/icon_big.png", /* for backward compatibility */

/**  Development information */
  'status_version'=> "1.0",
  'status'=> "Beta",
  'date'=> "Unreleased",
  'author_word'=> "",

/** Contributors */
  'developer_website_url' => "http://mekdrop.name",
  'developer_website_name' => "MekDrop.Name",
  'developer_email' => "i.know@mekdrop.name");

$modversion['people']['developers'][] = "MekDrop";
//$modversion['people']['testers'][] = "";
//$modversion['people']['translators'][] = "";
//$modversion['people']['documenters'][] = "";
//$modversion['people']['other'][] = "";

/** Manual */
$modversion['manual']['wiki'][] = "<a href='http://wiki.impresscms.org/index.php?title=imSources' target='_blank'>English</a>";

$modversion['warning'] = _CO_ICMS_WARNING_BETA;

/** Administrative information */
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

/** Database information */
$modversion['object_items'] = array('data', 'type', 'exporter', 'relation');

$modversion["tables"] = icms_getTablesArray($modversion['dirname'], $modversion['object_items']);

/** Install and update informations */
$modversion['onInstall'] = "include/onupdate.inc.php";
$modversion['onUpdate'] = "include/onupdate.inc.php";

/** Menu information */
$modversion['hasMain'] = 0;

/** Templates information */
$modversion['templates'] = array(
   array(
	'file' => 'imsources_admin_type.html',
	'description' => 'type Admin Index'),
	array(
  'file' => 'imsources_admin_source.html',
  'description' => 'source Admin Index'),
	array(
  'file' => 'imsources_admin_exporter.html',
  'description' => 'exporter Admin Index'));

 /** Autotasks **/
$modversion['autotasks'][] = array(
	'enabled' => true,
	'name' => _MI_IMSOURCES_ATNAME1,
	'code' => 'include/autotask_import.php',
	'interval' => 1440
);
$modversion['autotasks'][] = array(
	'enabled' => true,
	'name' => _MI_IMSOURCES_ATNAME2,
	'code' => 'include/autotask_autopost.php',
	'interval' => 1440
);
$modversion['autotasks'][] = array(
	'enabled' => true,
	'name' => _MI_IMSOURCES_ATNAME3,
	'code' => 'include/autotask_clearold.php',
	'interval' => 10440
);
  
?>