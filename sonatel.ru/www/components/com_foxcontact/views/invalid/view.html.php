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

require_once JPATH_COMPONENT . "/lib/functions.php";

class FoxContactViewInvalid extends JView
{
	function display($tpl = null)
	{
		$language = JFactory::getLanguage();
		$application = JFactory::getApplication("site");
		$menu = $application->getMenu();
		echo("<h2>" . $language->_($GLOBALS["COM_NAME"] . "_ERR_PROVIDE_VALID_URL") . "</h2>");
		$valid_items = $menu->getItems("component", $GLOBALS["com_name"]);
		echo("<ul>");
		foreach ($valid_items as &$valid_item)
		{
			echo('<li><a href="' . FGetLink($valid_item->id) . '">' . $valid_item->title . '</a></li>');
		}
		echo("</ul>");

		// See the documentation string
		$language->load('com_foxcontact.sys', JPATH_ADMINISTRATOR);
		echo('<p><a href="http://www.fox.ra.it/forum/22-how-to/1574-hide-the-contact-page-menu-item.html">' . $language->_($GLOBALS["COM_NAME"] . "_DOCUMENTATION") . "</a></p>");
	}
}