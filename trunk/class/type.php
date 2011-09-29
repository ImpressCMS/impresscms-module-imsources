<?php

/**
* Classes responsible for managing imSources type objects
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

class ImsourcesType extends IcmsPersistableObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ImsourcesPostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('type_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('name', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('code', XOBJ_DTYPE_SOURCE, true);
		$this->quickInitVar('dginterval', XOBJ_DTYPE_INT, true, null, null, 24);
		$this->quickInitVar('dgntime', XOBJ_DTYPE_LTIME, false);
		
		$this->hideFieldFromForm('dgntime');

	}

	public function exec() {
		var_dump($this->getVar('name'));
		if ($this->getVar('code') == '') return false;
		$result = eval($this->getVar('code'));
		$data_handler = &icms_getmodulehandler('data');
		foreach ($result as $data) {
			$data_handler->importItem($this->getVar('type_id'), $data['author'], $data['date'], $data['title'], $data['content'], $data['href']);
		}
		$this->setVar('dgntime', time() + $this->getVar('dginterval') * 3600);
		$this->store(true);
		return true;
	}
	
}
class ImsourcesTypeHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'type', 'type_id', 'name', 'code', 'imsources');
	}

	public function getTypesForExecution() {
		require_once ICMS_ROOT_PATH . '/class/criteria.php';
		$cr = new CriteriaCompo();
		$cr->add(new Criteria('dgntime', time(), '=<' ));
		return $this->getObjects();
	}
	
}
?>