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
require_once($inc_dir . '/fnewsletter.php');

class FAcyMailing extends FNewsletter
{
	const subscribe = 1;
	const unsubscribe = -1;

	public function __construct(&$params, &$messages, &$fieldsbuilder)
	{
		parent::__construct($params, $messages, $fieldsbuilder);
		$this->Name = "FAcyMailing";
		$this->prefix = "acymailing";
	}


	public function Process()
	{
		// Newsletter component disabled or not found. Aborting.
		if (!$this->enabled) return true;

		$config = acymailing_config();

		// Build subscriber object
		$subscriber = new stdClass;

		// Name field may be absent. AcyMailing will guess the user's name from his email address
		$subscriber->name = isset($this->FieldsBuilder->Fields['sender0']) ? $this->FieldsBuilder->Fields['sender0']['Value'] : "";

		// AcyMailing refuses to save the user (return false) if the email address is empty, so we don't care to check it
		$subscriber->email = empty($this->FieldsBuilder->Fields['sender1']['Value']) ? NULL : JMailHelper::cleanAddress($this->FieldsBuilder->Fields['sender1']['Value']);

		// It seems that $subscriber->confirmed defaults to unconfirmed if unset, so we need to read and pass the actual value from the configuration
		//ADRIEN : not necessary, you should keep the user as unconfirmed, Acy will take care of that
		//$subscriber->confirmed = !(bool)$config->get('require_confirmation');

		$userClass = acymailing_get('class.subscriber');
		$userClass->checkVisitor = false;

		// Add or update the user
		$sub_id = $userClass->save($subscriber);

		if (empty($sub_id))
		{
			// User save failed. Probably email address is empty or invalid
			$this->logger->Write(get_class($this) . " Process(): User save failed");
			return true;
		}

		// Lists
		$cumulative = JRequest::getVar("acymailing_subscribe_cumulative", NULL, "POST");
		$checkboxes = array(FAcyMailing::subscribe => JRequest::getVar("acymailing_subscribe", array(), "POST"));
		$lists = $cumulative ? $checkboxes : array();

		// Subscription
		//$listsubClass = acymailing_get('class.listsub');
		//$listsubClass->addSubscription($sub_id, $lists);
		//ADRIEN : we use an other function so Acy will check the subscription and only subscribe the user if he was not already subscribed to that list.
		$newSubscription = array();
		if(!empty($lists)){
			foreach($lists[FAcyMailing::subscribe] as $listId){
				$newList = array();
				$newList['status'] = FAcyMailing::subscribe;
				$newSubscription[$listId] = $newList;
			}
			$userClass->saveSubscription($sub_id,$newSubscription);
		}

		// implode() doesn't accept NULL values :(
		@$lists[FAcyMailing::subscribe] or $lists[FAcyMailing::subscribe] = array();

		// Log
		$this->logger->Write(get_class($this) . " Process(): subscribed "
		. $this->FieldsBuilder->Fields['sender0']['Value'] . " (". $this->FieldsBuilder->Fields ['sender1']['Value']
		. ") to lists " . implode(",", $lists[FAcyMailing::subscribe]));

		return true;
	}


	protected function load_newsletter_config()
	{
		if (!(bool)$this->Params->get("acymailing")) return $this->enabled = false;

		$include = JPATH_ADMINISTRATOR . '/components/com_acymailing/helpers/helper.php';
		$this->enabled = (bool)@include_once($include);

		$found = $this->enabled ? " " : " not ";
		$this->logger->Write(get_class($this) . " Newsletter component" . $found . "found");
	}


	protected function load_newsletter_lists()
	{
		// Prepare at least an empty array
		$this->lists = array();

		// Is this newsletter integration enabled in the parameters?
		if (!$this->enabled) return;

		// Ensure that the table exists, otherwise an sql error will be raised: #1146 - Table 'db.prefix_newsletter' doesn't exist
		if (!$this->extension_exists("acymailing")) return;

		// Get the lists selected to be shown. Defaults to a null array
		$lists = $this->Params->get("acymailing_lists", array("NULL"));

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->select($db->$GLOBALS["quoteName"]("listid") . " as " . $db->$GLOBALS["quoteName"]("id") . "," . $db->$GLOBALS["quoteName"]("visible") . "," . $db->$GLOBALS["quoteName"]("name"));

		$query->from($db->$GLOBALS["quoteName"]("#__acymailing_list"));

		// Condition: Published
		$query->where($db->$GLOBALS["quoteName"]("published") . "=" . $db->quote("1"));
		// Do not use Visible as condition, so that invisible lists are hidden but usable

		// Condition: List selected to be shown
		$query->where($db->$GLOBALS["quoteName"]("listid") . " IN (" . implode(',', $lists) .")");

		// Condition: current language or "all" languages
		$query->where("(" . $db->$GLOBALS["quoteName"]("languages") . " LIKE " . $db->quote("%" . JFactory::getLanguage()->getTag() . "%") . " OR " . $db->$GLOBALS["quoteName"]("languages") . " LIKE " . $db->quote("%all%") . ")");

		// (Suggested by ADRIEN) Condition: only standard lists
		$query->where($db->$GLOBALS["quoteName"]("type") . "=" . $db->quote("list"));

		$query->order($db->$GLOBALS["quoteName"]("ordering"));

		$db->setQuery($query);

		// Get the definitive lists to be shown. Defaults to an empty array
		$this->lists = $db->loadAssocList() or $this->lists = array();
	}
}
