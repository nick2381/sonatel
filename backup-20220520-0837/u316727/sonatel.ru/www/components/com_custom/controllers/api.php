<?php

class CustomControllerApi extends JControllerLegacy {

	// ajax actions

	function test() {
		print 'Hello world';

		jexit();
	}

	function apartments() {
		$db = JFactory::getDbo();
		$result = array();

		$categoryId = 14;
		$query = 'select * from #__content
				where state = 1 and catid = ' . (int) $categoryId;
		$db->setQuery($query);
		foreach ($db->loadObjectList() as $row) {
			$result[] = array($row->id, $row->title);
		}

		echo json_encode($result);
		jexit();
	}

	function booked_dates() {
		$db = JFactory::getDbo();
		$start = JRequest::getVar('start');
		$end = JRequest::getVar('end');
		$apartId = JRequest::getInt('apart_id');
		$apartName = JRequest::getVar('apart_name');

		// select by apartments name
		if ($apartName != '') {
			$db->setQuery('select * from #__content '
					. 'where title = ' . $db->quote($apartName));
			$row = $db->loadObject();
			if ($row) {
				$apartId = $row->id;
			}
		}

		$query = 'select * from #__simplebooking
			where article_id = ' . $apartId . ' and '
				. ' date >= ' . $db->quote($start) . ' and date <= ' . $db->quote($end);
		$db->setQuery($query);
		$result = $db->loadObjectList();

		$events = array();
		if (is_array($result)) {
			foreach ($result as $row) {
				$events[$row->date] = $row;
			}
			$res = true;
		}
		else {
			$res = false;
		}

		echo json_encode(array(
			'result' => $res,
			'calendarEvents' => $events
		));
		jexit();
	}

}
