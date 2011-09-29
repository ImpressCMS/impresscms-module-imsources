<?php

/**
* Classes responsible for managing imSources exporter objects
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		MekDrop <i.know@mekdrop.name>
* @package		imsources
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// including the IcmsPersistabelSeoObject
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableobject.php';
include_once(ICMS_ROOT_PATH . '/modules/imsources/include/functions.php');

class ImsourcesExporter extends IcmsPersistableObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ImsourcesPostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('exporter_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('name', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('type_id', XOBJ_DTYPE_INT, true);		
		$this->quickInitVar('default_title', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('default_author', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('module', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('object', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('field_author', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('field_title', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('field_content', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('field_date', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('post_user', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('template', XOBJ_DTYPE_SOURCE, true );
		$this->quickInitVar('max_time', XOBJ_DTYPE_INT, true, null, null, 2);
		$this->quickInitVar('enabled', XOBJ_DTYPE_INT, true);
		
		$vars = &$this->getVars();
		foreach ($vars as $var => $data) {
			if (isset($_POST[$var])) {
				if ($var == 'module') {
					$this->setVar($var, (int)$_POST[$var]);
				} else {
					$this->setVar($var, $_POST[$var]);
				}
			} elseif ($var == 'template') {
				$this->setVar('template', file_get_contents( dirname(dirname(__FILE__)) . '/templates/default_exporter.html' ));	
			}
		}
		unset($vars, $var, $data);
				
		$this->setControl('type_id', array('itemHandler' => 'type',
			                                    'method' => 'getList',
					                            'module' => 'imsources'));
		$this->setControl('module', array('object' => $this,
			                                    'method' => 'getPossibleModulesList',
												'onSelect' => 'submit'));
		$this->setControl('object', array('object' => $this,
			                                    'method' => 'getPossibleObjectsForModule',
												'onSelect' => 'submit'));
		$this->setControl('field_author', array('object' => $this,
			                                    'method' => 'getPossibleFieldsForObject',
												));
		$this->setControl('field_title', array('object' => $this,
			                                    'method' => 'getPossibleFieldsForObject',
												));
		$this->setControl('field_content', array('object' => $this,
			                                    'method' => 'getPossibleFieldsForObject',
												));										
		$this->setControl('field_date', array('object' => $this,
			                                    'method' => 'getPossibleFieldsForObject',
												));													
		$this->setControl('enabled', 'yesno');
		$this->setControl('post_user', 'user');
		
		$this->setControl('template', array(
			'name' => 'source',
			'syntax' => 'html'
		));		
		
	}
	
	public function getPossibleModulesList() {
		$modules_handler = &xoops_getHandler('module');
		require_once ICMS_ROOT_PATH .'/class/criteria.php';
		$cr = new CriteriaCompo();
		$cr->add(new Criteria('isactive', 1));
		$modules = $modules_handler->getObjects($cr);
		$ret = array();
		foreach ($modules as $module) {
			$ret[$module->getVar('mid')] = $module->getVar('name');
		}
		return $ret;
	}
	
	public function getPossibleObjectsForModule() {
		$result = array();		
		$modules_handler = &xoops_getHandler('module');		
		$module = $this->getModuleDirName();
		if (!$module) {
			return $result;
		}		
		$dir = ICMS_MODULES_PATH . '/' . $module . '/class';
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {			
				if (strtolower(substr($file, strlen($file) - 4)) != '.php')  continue;				
				$object = substr($file, 0, strrpos($file, '.'));				
				$object_handler = &icms_getmodulehandler($object, $module, $module, true);
				if (is_object($object_handler)) {
					$result[] = ucfirst($object);
				}				
			}
			closedir($dh);
			sort($result);
		}
		return $result;
	}
	
	public function getPossibleFieldsForObject() {
		$result = array();
		$object_handler = &$this->getSelectedObjectHandler();
		if (!$object_handler) return $result;
		$item = &$object_handler->create();
		$fields = &$item->getVars();
		foreach ($fields as $field => $data) {
			$result[] = $field;
		}
		sort($result);
		return $result;
	}

	public function getData() {
		$data_handler = &icms_getmodulehandler('data', 'imsources');
		$relations_handler = &icms_getmodulehandler('relation', 'imsources');
	
		require_once ICMS_ROOT_PATH . '/class/criteria.php';
		$cr = new CriteriaCompo();
		$last_time = strtotime(' -'.$this->getVar('max_time').' days');		
		$cr->add(new Criteria('date', $last_time, '>=' ));
		$cr->add(new Criteria('type_id', $this->getVar('type_id')));
		$data_bad_items = &$relations_handler->getLinkedDataIDs($this);
		//var_dump($data_bad_items . 'a');
		if (count($data_bad_items) > 0) {
			$cr->add(new Criteria('data_id', '(' . implode(',', $data_bad_items ). ')', 'NOT IN' ));		
		}		
		var_dump($cr->render());
		$cr->setSort('date');
		$cr->setOrder('ASC');
		return $data_handler->getObjects($cr);
	}
	
	public function getFieldName($type) {
		$title_field_id = (int)$this->getVar('field_' . $type);
		$fields = &$this->getPossibleFieldsForObject();
		if (!isset($fields[$title_field_id]) ) {
			return null;
		}					
		return $fields[$title_field_id];
	}
	
	public function getObjectName() {
		$object_id = (int)$this->getVar('object');
		$objects = $this->getPossibleObjectsForModule();
		$id = (int)$this->getVar('object');
		if (!isset($objects[$id]) ) {
			return null;
		}				
		return $objects[$id];
	}
	
	public function getModuleName() {
		$modules_handler = &xoops_getHandler('module');
		$module_obj = &$modules_handler->get((int)$this->getVar('module'));
		if (is_object($module_obj)) {
			return $module_obj->getVar('name');	
		}
		return null;
	}
	
	public function getModuleDirName() {
		$modules_handler = &xoops_getHandler('module');
		$module_obj = &$modules_handler->get((int)$this->getVar('module'));
		if (is_object($module_obj)) {
			return $module_obj->getVar('dirname');	
		}
		return null;
	}
	
	public function getSelectedObjectHandler() {		
		$module = $this->getModuleDirName();
		$object = $this->getObjectName();
		if (!$object || !$module) {
			return null;
		}
		$object_handler = &icms_getmodulehandler($object, $module, $module, false);
		return $object_handler;
	}
	
	public function getSmartCountNumber($update = true) {
		static $nr = null;
		if ($nr == null || $update) {
			require_once ICMS_ROOT_PATH . '/class/criteria.php';		
			$cr = new CriteriaCompo();				
			$cr->add(new Criteria($this->getFieldName('title'), addslashes(str_replace('<{$count}>', '%', $this->getVar('default_title', 'n'))), ' LIKE '));				
			$object_handler = &$this->getSelectedObjectHandler();		
			var_dump($cr->render());
			if (!$object_handler) {
				$nr = 1;
			} else {
				$nr = (int)$object_handler->getCount($cr) + 1;
			}
		} 
		return $nr;
	}
	
	public function getDisplayTitle() {
		return str_replace('<{$count}>', $this->getSmartCountNumber(), $this->getVar('default_title', 'n'));
	}
	
}
class ImsourcesExporterHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'exporter', 'exporter_id', 'name', 'template', 'imsources');
	}
	
	public function getExporters() {
		require_once ICMS_ROOT_PATH . '/class/criteria.php';
		$cr = new CriteriaCompo();
		$cr->add(new Criteria('enabled', 1));	
		return $this->getObjects($cr);
	}
	
}
?>