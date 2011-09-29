<?php
/**
* Admin page to manage exporters
*
* List, add, edit and delete exporter objects
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		MekDrop <i.know@mekdrop.name>
* @package		imsources
* @version		$Id$
*/

/**
 * Edit a Exporter
 *
 * @param int $exporter_id Exporterid to be edited
*/
function editexporter($exporter_id = 0)
{
	global $imsources_exporter_handler, $icmsModule, $icmsAdminTpl;

	$exporterObj = $imsources_exporter_handler->get($exporter_id);

	if (!$exporterObj->isNew()){
		$icmsModule->displayAdminMenu(0, _AM_IMSOURCES_EXPORTERS . " > " . _CO_ICMS_EDITING);
		$sform = $exporterObj->getForm(_AM_IMSOURCES_EXPORTER_EDIT, 'addexporter');
		$sform->assign($icmsAdminTpl);

	} else {
		$icmsModule->displayAdminMenu(0, _AM_IMSOURCES_EXPORTERS . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $exporterObj->getForm(_AM_IMSOURCES_EXPORTER_CREATE, 'addexporter');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:imsources_admin_exporter.html');
}

include_once("admin_header.php");

$imsources_exporter_handler = icms_getModuleHandler('exporter');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addexporter','del','view','');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_exporter_id = isset($_GET['exporter_id']) ? (int) $_GET['exporter_id'] : 0 ;

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
		
		$fieldObj = $imsources_exporter_handler->get($clean_exporter_id);

		if (isset($_POST['op'])) {
			$controller = new IcmsPersistableController($imsources_exporter_handler);
			$controller->postDataToObject($fieldObj);
			if ($_POST['op'] == 'changedField') {
				switch($_POST['changedField']) {
					case 'module':						
						$fieldObj->setVar('module', $_POST['module']);
					break;
				}
			}
		}		

  		editexporter($clean_exporter_id);
  		break;
  	case "addexporter":
          include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($imsources_exporter_handler);
  		$controller->storeFromDefaultForm(_AM_IMSOURCES_EXPORTER_CREATED, _AM_IMSOURCES_EXPORTER_MODIFIED);

  		break;

  	case "del":
  	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($imsources_exporter_handler);
  		$controller->handleObjectDeletion();

  		break;

  	case "view" :
  		$exporterObj = $imsources_exporter_handler->get($clean_exporter_id);

  		icms_cp_header();
  		smart_adminMenu(1, _AM_IMSOURCES_EXPORTER_VIEW . ' > ' . $exporterObj->getVar('exporter_name'));

  		smart_collapsableBar('exporterview', $exporterObj->getVar('exporter_name') . $exporterObj->getEditExporterLink(), _AM_IMSOURCES_EXPORTER_VIEW_DSC);

  		$exporterObj->displaySingleObject();

  		smart_close_collapsable('exporterview');

  		break;

  	default:

  		icms_cp_header();

  		$icmsModule->displayAdminMenu(1, _AM_IMSOURCES_EXPORTERS);

  		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
  		$objectTable = new IcmsPersistableTable($imsources_exporter_handler);
  		$objectTable->addColumn(new IcmsPersistableColumn('name'));
		$objectTable->addColumn(new IcmsPersistableColumn('enabled'));
		$objectTable->addColumn(new IcmsPersistableColumn('max_time'));

  		$objectTable->addIntroButton('addexporter', 'exporter.php?op=mod', _AM_IMSOURCES_EXPORTER_CREATE);
  		$icmsAdminTpl->assign('imsources_exporter_table', $objectTable->fetch());
  		$icmsAdminTpl->display('db:imsources_admin_exporter.html');
  		break;
  }
  icms_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
?>