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

class ImsourcesData extends IcmsPersistableObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ImsourcesPostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('data_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('type_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX, true);		
		$this->quickInitVar('href', XOBJ_DTYPE_TXTBOX, true);	
		$this->quickInitVar('date', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('author', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('content', XOBJ_DTYPE_TXTAREA, true);		
		$this->quickInitVar('hash', XOBJ_DTYPE_TXTBOX, true);

	}

	/**
	 * Overriding the IcmsPersistableObject::getVar method to assign a custom method on some
	 * specific fields to handle the value before returning it
	 *
	 * @param str $key key of the field
	 * @param str $format format that is requested
	 * @return mixed value of the field that is requested
	 */
	function getVar($key, $format = 's') {
		$value = parent :: getVar($key, $format);
		if ($key == 'hash' && $value == '') {
			$content = $this->getVar('content', 'n');
			$content = strip_tags($content);
			$content = str_replace(array("\t", "\r", "\n"),
											  " ",
											  $content);
			$old_content = '';
			while( $old_content != $content) {
				$old_content = $content;
				$content = str_replace('  ',' ', $content);	
			}
			$value = soundex($content).'-'.strlen($content);
			$this->setVar('hash', $value);
		}
		return $value;
	}
	
	public function isSaved() {
		require_once ICMS_ROOT_PATH . '/class/criteria.php';
		$cr = new CriteriaCompo();
		$cr->add(new Criteria('hash', $this->getVar('hash')));		
		$cr->setLimit(1);
		$count = (int)$this->handler->getCount($cr);
		return ($count > 0);
	}
	
	public function toArray() {
		$rez = parent::toArray();
		$rez['content'] = $this->getVar('content', 'n');
		return $rez;
	}

}
class ImsourcesDataHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'data', 'data_id', 'title', 'content', 'imsources');
	}
	
	public function simpleCreate($type_id, $author, $date, $title, $content, $href) {
		$obj = &$this->create();
		$obj->setVar('type_id', $type_id);
		$obj->setVar('title', $title);		
		$obj->setVar('href', $href);	
		$obj->setVar('date', $date);
		$obj->setVar('author', $author);		
		$obj->setVar('content', $content);
		return $obj;
	}
	
	public function exists($type_id, $author, $date, $title, $content, $href) {
		$item = &$this->simpleCreate($type_id, $author, $date, $title, $content, $href);
		return $item->isSaved();
	}

	public function importItem($type_id, $author, $date, $title, $content, $href) {
		$item = &$this->simpleCreate($type_id, $author, $date, $title, $content, $href);		
		if ($item->isSaved()) {
			return false;
		}			
		return $item->store();
	}
	
}
?>