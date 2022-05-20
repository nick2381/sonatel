<?php defined('_JEXEC') or die('Restricted access');
/*
This file is part of "Fox Joomla Extensions".

You can redistribute it and/or modify it under the terms of the GNU General Public License
GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html

You have the freedom:
* to use this software for both commercial and non-commercial purposes
* to share, copy, distribute and install this software and charge for it if you wish.
Under the following conditions:
* You must attribute the work to the original author by leaving untouched the link "powered by",
except if you obtain a "registerd version" http://www.fox.ra.it/forum/14-licensing/151-remove-the-backlink-powered-by-fox-contact.html

Author: Demis Palma
Documentation at http://www.fox.ra.it/forum/2-documentation.html
*/

jimport('joomla.application.component.view');

class FoxContactViewLoader extends JView
{
	function display($tpl = null)
	{
		// Load component || module parameters. Defaults to component
		$owner = JRequest::getVar("owner", "", "GET");

		// Only admit lowercase a-z, underscore and minus. Forbid numbers, symbols, slashes and other stuff.
		preg_match('/^[a-z_-]+$/', $owner) or $owner = "component";

		$method = "_get_" . $owner . "_params_";
		$params = $this->$method();

		$type = JRequest::getVar("type", "", "GET");

		// Import appropriate library
		//jimport("foxcontact.loader." . $type) or die("loader library not found");
		require_once JPATH_COMPONENT . "/lib/" . $type . ".php";

		// Instantiate the loader
		$classname = $type . "Loader";
		$loader = new $classname();
		$loader->Params = &$params;
		$loader->Show();
	}


	private function _get_component_params_()
	{
		// @ avoids Warning: ini_set() has been disabled for security reasons in /var/www/libraries/joomla/[...]
		$application = @JFactory::getApplication('site');  // Needed to get the correct session with JFactory::getSession() below
		$menu = @$application->getMenu();
		$params = $menu->getParams(intval(JRequest::getVar("id", 0, "GET")));
		return $params;
	}


	private function _get_module_params_()
	{
		$db = JFactory::getDbo();
		jimport("joomla.database.databasequery");
		$query = $db->getQuery(true);
		$query->select('`params`');
		$query->from('`#__modules`');
		$query->where("`id` = " . intval(JRequest::getVar("id", 0, "GET")));
		$query->where("`module` = 'mod_foxcontact'");
		$db->setQuery($query);

		// Load parameters from database
		$json = $db->loadResult();
		// Convert to JRegistry
		$params = new JRegistry($json);
		return $params;
	}


}