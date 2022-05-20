<?php

class CustomControllerBooking extends JControllerLegacy {

	function __construct($default = array()) {
		parent::__construct($default);

		//...
	}
	
	function display($tpl = null) {
		$view = JRequest::getCmd('view');
		if (empty($view)) {
			JRequest::setVar('view', 'booking');
		}
		
		parent::display();
	}

	//
	// ajax actions
	//

	function example() {
		echo 'test';

		jexit();
	}

}