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

class ImsourcesRelation extends IcmsPersistableObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ImsourcesPostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('relation_id', XOBJ_DTYPE_INT, true);		
		$this->quickInitVar('exporter_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('data_id', XOBJ_DTYPE_INT, true);

	}

}
class ImsourcesRelationHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'relation', 'relation_id', 'exporter_id', 'data_id', 'imsources');
	}
	
	public function isLinked(&$exporter, &$data) {
		require_once ICMS_ROOT_PATH . '/class/criteria.php';
		$cr = new CriteriaCompo();
		$cr->add(new Criteria('exporter_id', (int)$exporter->getVar('exporter_id')));
		$cr->add(new Criteria('data_id', (int)$data->getVar('data_id')));
		$cr->setLimit(1);
		return ((int)$this->getCount($cr) > 0);
	}
	
	public function link(&$exporter, &$data) {
		if ($this->isLinked($exporter, $data)) {
			return false;
		}
		$obj = &$this->create();
		$obj->setVar('exporter_id', (int)$exporter->getVar('exporter_id'));
		$obj->setVar('data_id', (int)$data->getVar('data_id'));
		return $obj->store(true);
	}	
	
	public function getLinked(&$exporter) {
		require_once ICMS_ROOT_PATH . '/class/criteria.php';
		$cr = new CriteriaCompo();
		$cr->add(new Criteria('exporter_id', (int)$exporter->getVar('exporter_id')));
		return $this->getObjects($cr);
	}
	
	public function getLinkedDataIDs(&$exporter) {
		$items = $this->getLinked($exporter);		
		$result = array();
		foreach ($items as $item) {
			$result[] = (int)$item->getVar('data_id');
		}
		return $result;
	}
	
}
?>