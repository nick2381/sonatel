<?php

class CustomControllerRelatedproducts extends JControllerLegacy {

	function __construct($default = array()) {
		parent::__construct($default);

		//...
	}

	function display($tpl = null) {
		$view = JRequest::getCmd('view');
		if (empty($view)) {
			JRequest::setVar('view', 'relatedproducts');
		}

		parent::display();
	}

	protected function deleteRelated($linkedId, $id) {
		$db = JFactory::getDbo();

		$query = 'delete from #__custom_relatedproducts
			where linked_id = ' . (int) $linkedId . ' and id = ' . (int) $id;
		$db->setQuery($query);
		$db->query();
	}

	protected function saveRelated($linkedId, $id, $diff_description) {
		$db = JFactory::getDbo();

		$query = 'insert into #__custom_relatedproducts
				(linked_id, id, diff_description)
				values (' . (int) $linkedId . ', ' . (int) $id . ', ' . $db->quote($diff_description) . ')
			on duplicate key update
				diff_description = ' . $db->quote($diff_description);

		$db->setQuery($query);
		$db->query();
	}

	protected function addRelated($linkedId, $id) {
		$db = JFactory::getDbo();

		$query = 'insert into #__custom_relatedproducts
				(linked_id, id, diff_description)
				values (' . (int) $linkedId . ', ' . (int) $id . ', "")';

		$db->setQuery($query);
		$db->query();
	}

	function edit() {
		$linkedId = JRequest::getVar('linked_id', '');
		if (!empty($linkedId)) {
			$actionsIds = JRequest::getVar('save', '');
			// save action
			if (!empty($actionsIds)) {
				$actionsIds = array_keys($actionsIds);
				$id = array_shift($actionsIds);
				$descriptions = $_REQUEST['diff_description'];
				$this->saveRelated($linkedId, $id, $descriptions[$id]);
			}
			// delete action
			else {
				$actionsIds = JRequest::getVar('del', '');
				if (!empty($actionsIds)) {
					$actionsIds = array_keys($actionsIds);
					$id = array_shift($actionsIds);
					$this->deleteRelated($linkedId, $id);
				}
			}
		}

		$this->setRedirect($_SERVER['REQUEST_URI']);
	}

	//
	// ajax actions
	//

	function add() {
		$linkedId = JRequest::getVar('linked_id');
		$id = JRequest::getVar('id');

		$this->addRelated($linkedId, $id);
		echo '1';

		jexit();
	}

}