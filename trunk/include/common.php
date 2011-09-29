<?php
/**
* Common file of the module included on all pages of the module
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		MekDrop <i.know@mekdrop.name>
* @package		imsources
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

if(!defined("IMSOURCES_DIRNAME"))		define("IMSOURCES_DIRNAME", $modversion['dirname'] = basename(dirname(dirname(__FILE__))));
if(!defined("IMSOURCES_URL"))			define("IMSOURCES_URL", ICMS_URL.'/modules/'.IMSOURCES_DIRNAME.'/');
if(!defined("IMSOURCES_ROOT_PATH"))	define("IMSOURCES_ROOT_PATH", ICMS_ROOT_PATH.'/modules/'.IMSOURCES_DIRNAME.'/');
if(!defined("IMSOURCES_IMAGES_URL"))	define("IMSOURCES_IMAGES_URL", IMSOURCES_URL.'images/');
if(!defined("IMSOURCES_ADMIN_URL"))	define("IMSOURCES_ADMIN_URL", IMSOURCES_URL.'admin/');

// Include the common language file of the module
icms_loadLanguageFile('imsources', 'common');

include_once(IMSOURCES_ROOT_PATH . "include/functions.php");

// Creating the module object to make it available throughout the module
$imsourcesModule = icms_getModuleInfo(IMSOURCES_DIRNAME);
if (is_object($imsourcesModule)){
	$imsources_moduleName = $imsourcesModule->getVar('name');
}

// Find if the user is admin of the module and make this info available throughout the module
$imsources_isAdmin = icms_userIsAdmin(IMSOURCES_DIRNAME);

// Creating the module config array to make it available throughout the module
$imsourcesConfig = icms_getModuleConfig(IMSOURCES_DIRNAME);

// creating the icmsPersistableRegistry to make it available throughout the module
global $icmsPersistableRegistry;
$icmsPersistableRegistry = IcmsPersistableRegistry::getInstance();

?>