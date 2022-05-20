<?php defined('_JEXEC') or die ('Restricted access');
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

	$inc_dir = realpath(dirname(__FILE__));
	require_once($inc_dir . '/fdispatcher.php');

	class FJMessenger extends FDispatcher
	{
		public function __construct(&$params, &$messages, &$fieldsbuilder)
		{
			parent::__construct($params, $messages, $fieldsbuilder);

			$this->isvalid = true;
		}


		public function Process()
		{
			$uid = $this->Params->get("jmessenger_user", NULL);
			// No user selected for Joomla messenger
			if (!$uid)
			{
				//JLog::add("No recipient selected in Joomla Messenger dispatcher. Private message was not send.", JLog::INFO, get_class($this));
				// It's not a problem. Maybe it's even wanted. Return succesful.
				return true;
			}

			$body = $this->body();
			$body .= $this->attachments();
			$body .= PHP_EOL;
			// Info about url
			$body .= $this->Application->getCfg("sitename") . " - " . $this->CurrentURL() . PHP_EOL;
			// Info about client
			$body .= "Client: " . $this->ClientIPaddress() . " - " . $_SERVER['HTTP_USER_AGENT'] . PHP_EOL;

			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			$query->insert("#__messages");
			$query->set($db->$GLOBALS["quoteName"]("user_id_from") . "=" . $db->quote($uid));
			$query->set($db->$GLOBALS["quoteName"]("user_id_to") . "=" . $db->quote($uid));
			$query->set($db->$GLOBALS["quoteName"]("date_time") . "=" . $db->quote(JFactory::getDate()->$GLOBALS["toSql"]()));
			$query->set($db->$GLOBALS["quoteName"]("subject") . "=" . $db->quote($this->submittername() . " (" . $this->submitteraddress() . ")"));
			$query->set($db->$GLOBALS["quoteName"]("message") . "=" . $db->quote(JMailHelper::cleanBody($body)));

			$db->setQuery((string)$query);

			if (!$db->query())
			{
				$msg = JText::_($GLOBALS["COM_NAME"] . "_ERR_SENDING_MESSAGE");
				//JLog::add($msg, JLog::ERROR, get_class($this));
				$this->Messages[] = $msg;
				// Database problems. Return error.
				return false;
			}

			//JLog::add("Private message sent to Joomla messenger.", JLog::INFO, get_class($this));
			return true;

		}


		protected function attachments()
		{
			$result = "";
			// this message is for the webmaster
			if (count($this->FileList)) $result .= $this->Language->_($GLOBALS["COM_NAME"] . "_UPLOAD_LBL") . PHP_EOL;
			foreach ($this->FileList as &$file)
			{
				$result .= JUri::base() . 'components/' . $GLOBALS["com_name"] . '/uploads/' . $file . PHP_EOL;
			}

			return $result;
		}


	}

?>
