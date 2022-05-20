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

$GLOBALS["ext_name"] = basename(__FILE__);
$GLOBALS["com_name"] = dirname(__FILE__);
$GLOBALS["mod_name"] = realpath(dirname(__FILE__) . "/../../modules");
$GLOBALS["EXT_NAME"] = strtoupper($GLOBALS["ext_name"]);
$GLOBALS["COM_NAME"] = strtoupper($GLOBALS["com_name"]);
$GLOBALS["MOD_NAME"] = strtoupper($GLOBALS["mod_name"]);
$GLOBALS["left"] = false;
$GLOBALS["right"] = true;

$application = JFactory::getApplication('site');
$menu = $application->getMenu();
// JMenu::getActive() is inconsistent. It doesn't return an object everytime.
$activemenu = $menu->getActive() or $activemenu = new stdClass();
$application->owner = "component";
$application->oid = isset($activemenu->id) ? $activemenu->id : 0;
$application->cid = isset($activemenu->id) ? $activemenu->id : 0;
$application->mid = 0;
$application->submitted = (bool)count($_POST) && isset($_POST["cid_$application->cid"]);
$me = basename(__FILE__);
$name = substr($me, 0, strrpos($me, '.'));
include(realpath(dirname(__FILE__) . "/" . $name . ".inc"));

jimport('joomla.application.component.controller');
$controller = JController::getInstance('FoxContact');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();

