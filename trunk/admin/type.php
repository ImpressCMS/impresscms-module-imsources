<?php
/**
* Admin page to manage types
*
* List, add, edit and delete type objects
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		MekDrop <i.know@mekdrop.name>
* @package		imsources
* @version		$Id$
*/

/**
 * Edit a Type
 *
 * @param int $type_id Typeid to be edited
*/
function edittype($type_id = 0)
{
	global $imsources_type_handler, $icmsModule, $icmsAdminTpl;

	$typeObj = $imsources_type_handler->get($type_id);

	if (!$typeObj->isNew()){
		$icmsModule->displayAdminMenu(0, _AM_IMSOURCES_TYPES . " > " . _CO_ICMS_EDITING);
		$sform = $typeObj->getForm(_AM_IMSOURCES_TYPE_EDIT, 'addtype');
		$sform->assign($icmsAdminTpl);

	} else {
		$icmsModule->displayAdminMenu(0, _AM_IMSOURCES_TYPES . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $typeObj->getForm(_AM_IMSOURCES_TYPE_CREATE, 'addtype');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:imsources_admin_type.html');
}

include_once("admin_header.php");

$imsources_type_handler = icms_getModuleHandler('type');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addtype','del','view','');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_type_id = isset($_GET['type_id']) ? (int) $_GET['type_id'] : 0 ;

/**
 * in_array() is a native PHP function that will determine if the value of the
 * first argument is found in the array listed in the second argument. Strings
 * are case sensitive and the 3rd argument determines whether type matching is
 * required
*/
if (in_array($clean_op,$valid_op,true)){
  switch ($clean_op) {
  	case "mod":
  	case "changedField":

  		icms_cp_header();

  		edittype($clean_type_id);
  		break;
  	case "addtype":
          include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($imsources_type_handler);
  		$controller->storeFromDefaultForm(_AM_IMSOURCES_TYPE_CREATED, _AM_IMSOURCES_TYPE_MODIFIED);

  		break;

  	case "del":
  	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($imsources_type_handler);
  		$controller->handleObjectDeletion();

  		break;

  	case "view" :
  		$typeObj = $imsources_type_handler->get($clean_type_id);

  		icms_cp_header();
  		smart_adminMenu(1, _AM_IMSOURCES_TYPE_VIEW . ' > ' . $typeObj->getVar('type_name'));

  		smart_collapsableBar('typeview', $typeObj->getVar('type_name') . $typeObj->getEditTypeLink(), _AM_IMSOURCES_TYPE_VIEW_DSC);

  		$typeObj->displaySingleObject();

  		smart_close_collapsable('typeview');

  		break;

  	default:

  		icms_cp_header();

  		$icmsModule->displayAdminMenu(0, _AM_IMSOURCES_TYPES);

  		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
  		$objectTable = new IcmsPersistableTable($imsources_type_handler);
  		$objectTable->addColumn(new IcmsPersistableColumn('name'));
		$objectTable->addColumn(new IcmsPersistableColumn('dginterval'));
		$objectTable->addColumn(new IcmsPersistableColumn('dgntime'));		

  		$objectTable->addIntroButton('addtype', 'type.php?op=mod', _AM_IMSOURCES_TYPE_CREATE);
  		$icmsAdminTpl->assign('imsources_type_table', $objectTable->fetch());
  		$icmsAdminTpl->display('db:imsources_admin_type.html');
  		break;
  }
  icms_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
?>