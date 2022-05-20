<?php defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controller');
 
class FoxContactController extends JController
{
	// display task
	function display($cachable = false) 
	{
		// set default view if not set
		JRequest::setVar("view", JRequest::getCmd("view", "Dashboard"));
 
		// call parent behavior
		parent::display($cachable);
	}
}
