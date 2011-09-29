<?php
/**
* Footer page included at the end of each page on user side of the mdoule
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		MekDrop <i.know@mekdrop.name>
* @package		imsources
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

$icmsTpl->assign("imsources_adminpage", imsources_getModuleAdminLink());
$icmsTpl->assign("imsources_is_admin", $imsources_isAdmin);
$icmsTpl->assign('imsources_url', IMSOURCES_URL);
$icmsTpl->assign('imsources_images_url', IMSOURCES_IMAGES_URL);

$xoTheme->addStylesheet(IMSOURCES_URL . 'module'.(( defined("_ADM_USE_RTL") && _ADM_USE_RTL )?'_rtl':'').'.css');

include_once(ICMS_ROOT_PATH . '/footer.php');

?>