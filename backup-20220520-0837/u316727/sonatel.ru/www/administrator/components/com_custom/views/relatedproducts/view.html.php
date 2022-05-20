<?php

class CustomViewRelatedproducts extends JViewLegacy {

	protected function getItems($linkedId) {
		$db = JFactory::getDbo();
		
		$query = 'select a.id as product_id, a.title, r.* from #__content as a
			left join #__custom_relatedproducts as r on a.id = r.id
			where r.linked_id = ' . (int)$linkedId;
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
	
	function display($tpl = null) {
		$user = JFactory::getUser();

		// get request vars
		$option = JRequest::getCmd('option');
		$controller = JRequest::getWord('controller');
		$edit = JRequest::getVar('edit', true);

		$linkedId = JRequest::getVar('linked_id', '');		
		// filtered ids
		$ids = explode(',', JRequest::getVar('ids', ''));		
		foreach ($ids as $i => $id) {
			if (trim($id) == '' || !(int)$id) {
				unset($ids[$i]);
			}
		}
		
		$items = $this->getItems($linkedId);		
		$lists = array();
		
		// set template vars
		$this->assignRef('user', $user);
		$this->assignRef('option', $option);
		$this->assignRef('controller', $controller);
		$this->assignRef('lists', $lists);
		$this->assignRef('items', $items);
		$this->assignRef('linkedId', $linkedId);	

		parent::display($tpl);
	}

}