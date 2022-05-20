<?php
defined('_JEXEC') or die;

$db = JFactory::getDbo();
$catalogCategoryId = 14;
	
$categoryId = JRequest::getVar('view') == 'category'? 
		JRequest::getVar('id') : JRequest::getVar('catid');

$query = 'select * from #__categories
	where id = ' . (int) $categoryId;
$db->setQuery($query);		
$row = $db->loadObject();
while ($row && $row->parent_id && $row->parent_id != $catalogCategoryId) {
	// check parent id
	$query = 'select * from #__categories
			where id = ' . (int) $categoryId;
	$db->setQuery($query);
	$row = $db->loadObject();
	
	$categoryId = $row->parent_id;				
}

if ($row) {
	$start = stripos($row->description, '<hr');
	if ($start !== false) {	
		$end = strpos($row->description, '>', $start);
		echo substr($row->description, 0, $start);
	}
}