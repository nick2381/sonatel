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

require_once(realpath(dirname(__FILE__) . '/foxinstall.php'));

class com_foxcontactInstallerScript extends FoxInstaller
{
	function update($parent)
	{
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/helpers/debug-on.php');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/helpers/debug-off.php');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/helpers/fcaptcha-drawer.php');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/helpers/functions.php');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/helpers/qqfileuploader.php');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/helpers/vfdebugger.php');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/helpers/fmailer.php');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/lib/captcha-drawer.php');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/lib/file-uploader.php');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/lib/debug-on.php');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/lib/debug-off.php');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/js/dropdown.js');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/js/dropdown-min.js');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/js/fcaptcha.js');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/js/fcheckbox.js');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/js/fcheckbox-min.js');
		@unlink(JPATH_ROOT . '/components/' . $parent->get('element') . '/js/fileuploader-min.js');

		@unlink(JPATH_ADMINISTRATOR . '/components/' . $parent->get('element') . '/models/fields/vfheader.php');
		@unlink(JPATH_ADMINISTRATOR . '/components/' . $parent->get('element') . '/models/fields/vftext.php');
		@unlink(JPATH_ADMINISTRATOR . '/components/' . $parent->get('element') . '/models/fields/vftextarea.php');
		@unlink(JPATH_ADMINISTRATOR . '/components/' . $parent->get('element') . '/models/fields/fsql.php');
		@unlink(JPATH_ADMINISTRATOR . '/components/' . $parent->get('element') . '/foxcontact.inc');
		parent::install($parent);
	}
}