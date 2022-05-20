<?php

defined('_JEXEC') or die('Restricted access');
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
require_once($inc_dir . '/fdatapump.php');
require_once($inc_dir . '/flanghandler.php');
require_once($inc_dir . '/flogger.php');

class FieldsBuilder extends FDataPump {

	public function __construct(&$params, &$messages) {
		parent::__construct($params, $messages);

		$this->ValidateEmail(); // email can have text without being valid
		// Load required js only once
		if (!isset($GLOBALS[$GLOBALS["ext_name"] . '_js_loaded'])) {
			// We need mootools to be load before than our scripts
			JFactory::getDocument()->addScript(JUri::base(true) . "/media/system/js/mootools-core.js");
			JFactory::getDocument()->addScript(JUri::base(true) . "/media/system/js/mootools-more.js");
			JFactory::getDocument()->addScript(JUri::base(true) . "/index.php?option=" . $GLOBALS["com_name"] . "&amp;view=loader&amp;owner=" . $this->Application->owner . "&amp;id=" . $this->Application->oid . "&amp;type=js&amp;filename=jtext");
			JFactory::getDocument()->addScript(JUri::base(true) . "/components/" . $GLOBALS["com_name"] . "/js/fileuploader.js");
			$GLOBALS[$GLOBALS["ext_name"] . '_js_loaded'] = true;
		}


		$this->isvalid = intval($this->ValidateForm()); // Are all fields valid?

		$lang_handler = new FLangHandler();

		if ($lang_handler->HasMessages())
			$messages += $lang_handler->GetMessages();
	}

	public function count_fields(&$fields, $type) {
		// Todo: if $type is "text", it count every field starting with "text", so textarea fields are considered "text" field
		// but this is not really a problem
		$result = 0;
		$type_len = strlen($type);
		foreach ($fields as $fname => $fvalue) {
			if (
					substr($fname, 0, $type_len) == $type && // item starts with $type
					substr($fname, strlen($fname) - 7) == "display" // item ends with "display"
			)
				++$result;
		}
		return $result;
	}

	public function Show() {
		$result = "";
		uasort($this->Fields, "sort_fields");

		foreach ($this->Fields as $key => $field) {
			switch ($field['Type']) {
				case 'customhtml':
					$result .= $this->BuildCustomHtmlField($key, $field);
					break;
				case 'sender':
				case 'text':
					$result .= $this->BuildTextField($key, $field); //Example: $this->BuildTextField('sender0', $field)
					break;
				case 'dropdown':
					$result .= $this->BuildDropdownField($key, $field); //Example: $this->BuildTextField('dropdown0', $field)
					break;
				case 'textarea':
					$result .= $this->BuildTextareaField($key, $field); //Example: $this->BuildTextField('textarea0', $field)
					break;
				case 'checkbox':
					$result .= $this->BuildCheckboxField($key, $field); //Example: $this->BuildTextField('checkbox0', $field)
					break;
			}

			if (!$field["IsValid"])
				$this->Messages[] = JTEXT::sprintf($GLOBALS["COM_NAME"] . '_ERR_INVALID_VALUE', $field["Name"]);
		}

		return $result;
	}

	protected function LoadFields() {
		$fields = $this->Params->toArray();
		$text_count = $this->count_fields($fields, "text");
		$dropdown_count = $this->count_fields($fields, "dropdown");
		$textarea_count = $this->count_fields($fields, "textarea");
		$checkbox_count = $this->count_fields($fields, "checkbox");

		// Loads parameters and $_POST data
		$this->LoadField("labels", "");
		$this->LoadField("customhtml", 0);
		for ($n = 0; $n < 2; ++$n)
			$this->LoadField("sender", $n);
		for ($n = 0; $n < $text_count; ++$n)
			$this->LoadField("text", $n);
		for ($n = 0; $n < $dropdown_count; ++$n)
			$this->LoadField("dropdown", $n);
		for ($n = 0; $n < $textarea_count; ++$n)
			$this->LoadField("textarea", $n);
		for ($n = 0; $n < $checkbox_count; ++$n)
			$this->LoadField("checkbox", $n);
		$this->LoadField("customhtml", 1);
	}

	protected function LoadField($type, $number) { // Example: 'text', '0'
		// Load component parameters
		$name = $type . (string) $number; // Example: 'text0'
		// If not to be displayed, it's useless to continue reading other values
		if (!parent::LoadField($type, $name))
			return false;
		// Load data
		$this->Fields[$name]['Value'] = htmlspecialchars(JRequest::getVar($this->Fields[$name]['PostName'],
						NULL, 'POST'));

		// Additional manipulations
		if ($this->Fields[$name]['Value'] == $this->Fields[$name]['Name']) { // Example: Field='Your name' Value='Your name'
			// Seems like a submission from the module without filling the field, so let's invalidate the value!
			$this->Fields[$name]['Value'] = "";
		}

		// Validation after *all* fields are loaded and manipulated
		$this->Fields[$name]['IsValid'] = intval($this->ValidateField($this->Fields[$name]['Value'],
						$this->Fields[$name]['Display']));

		// Checkboxes need to be manipulated after validation, otherwise a JNO value will be considered valid
		// Checkboxes have only JYES or empty values. Translate empty to JNO
		if ($type == "checkbox" && $this->Fields[$name]['Value'] == "") {
			$this->Fields[$name]['Value'] = JText::_('JNO');
		}

		return true;
	}

	private function BuildCustomHtmlField($key, &$field) {
		if (empty($field['Name']))
			return;

		$result = '<div class="form-field foxfield fxf-' . $key . '">' .
				$field['Name'] .
				"</div>" . PHP_EOL;

		return $result;
	}

	// Build a single Text field
	private function BuildTextField($key, &$field) {
		// Todo: we don't need this check. This function is called only for required and optional items
		if (!isset($field['Display']) || !(bool) $field['Display'])
			return;

		//$myownclass = preg_replace("/[^a-z0-9]/", "", strtolower($field["Name"]));
		// Todo: duplicated code
		switch (intval($this->Params->get("labelsdisplay"))) {
			case 0:
				// Labels inside
				// If a value was submittet use it as text, otherwise use the field name
				//$value = $field['Value'] ? $field['Value'] : $field['Name'];
				$value = $field['Value'];
				$external_label = '';
				//$js = "onfocus=\"if(this.value==this.title) this.value='';\" onblur=\"if(this.value=='') this.value=this.title;\" ";
				$js = 'placeholder="' . $field['Name'] . '"';
				break;

			default:
				// Labels outside
				$value = $field['Value'];
				$external_label = $this->build_label($field);
				$js = "";
		}

		$additional_style = "";
		if (!intval($this->Params->get("labelsdisplay"))) {
			$additional_style = 'display:block;' .
					'float:none;' .
					'margin:0 auto !important;';
		}

		$required = ($field['Display'] == 2 ? ' required ' : '');
		$result = '<div class="form-field foxfield fxf-' . $key . $required . '">' .
				$external_label .
				'<input ' .
				// 'class="' . $this->TextStyleByValidation($field) . ' ' . $myownclass . '" ' .
				'class="' . $this->TextStyleByValidation($field) . '" ' .
				'type="text" ' .
				'value="' . $value . '" ' .
				'title="' . $field['Name'] . '" ' . $required;

		if ($field['Width']) {
			$result .= 'style="' .
					'width:' . $field['Width'] . $field['Unit'] . ' !important;' .
					//$additional_style .
					'" ';
		}

		$result .= 'name="' . $field['PostName'] . '" ' .
				$js . '/>'; //. $this->DescriptionByValidation($field);  // Example: *
		$result .= "</div>" . PHP_EOL;

		return $result;
	}

	// Build a single Dropdown box field
	private function BuildDropdownField($key, &$field) {
		// Todo: we don't need this check. This function is called only for required and optional items
		if (!isset($field['Display']) || !(bool) $field['Display'])
			return;

		// Todo: duplicated code
		switch (intval($this->Params->get("labelsdisplay"))) {
			case 0:
				// Labels inside
				// If a value was submitted use it as text, otherwise use the field name
				$value = $field['Value'] ? $field['Value'] : $field['Name'];
				$external_label = "";
				$js = "onfocus=\"if(this.value==this.title) this.value='';\" onblur=\"if(this.value=='') this.value=this.title;\" ";
				break;

			default:
				// Labels outside
				$value = $field['Value'];
				$external_label = $this->build_label($field);
				$js = "";
		}

		$additional_style = "";
		if (!intval($this->Params->get("labelsdisplay"))) {
			$additional_style = 'display:block;' .
					'float:none;' .
					'margin:0 auto !important;';
		}

		$result = '<div class="form-field foxfield fxf-' . $key . '">'.
				$external_label . '<div class="styled-select" ';
		if ($field['Width']) {
			$result .= 'style="' .
					'width:' . $field['Width'] . $field['Unit'] . ';' .
					$additional_style .
					'" ';
		}
		$result .= '>' .		
				'<select class="foxdropdown ' . $this->TextStyleByValidation($field) . '" ' .
				'name="' . $field['PostName'] . '" ' .
				($field['Display'] == 2 ? ' required ' : '') .
				//'style="' .
				//'width:' . $field['Width'] . $field['Unit'] . ' !important; ' .
				//'width:100% !important; opacity: 1 !important;' .
				//'" ' .
				//'onchange="DropdownAlignValue(this)" ' .
				'>';

		// The first option may be the field label
		if (intval($this->Params->get("labelsdisplay")) == 0) {
			$result .= '<option value="">' . trim($field['Name']) . '</option>';
		}
		else {
			$result .= '<option value=""></option>';
		}
		
		// and the actual options
		$options = explode(",", $field['Values']);
		for ($o = 0; $o < count($options); ++$o) {
			$result .= "<option value='" . trim($options[$o]) . "'";
			if ($field['Value'] === $options[$o] && !empty($options[$o])) {
				$result .= " selected ";
			}
			$result .= ">" . $options[$o] . "</option>";
		}
		$result .= "</select>";

		/*
		  $result .= '<span class="outer_dropdown ' . $this->FieldStyleByValidation($field) . '" ' .
		  '>';
		  $result .= '<span class="inner_dropdown" ' .
		  'style="' .
		  'background-image:url(' . JUri::base(true) . '/media/' . $GLOBALS["com_name"] . '/images/dropdown-arrow-' . $GLOBALS["right"] . '.png);' .
		  'background-position:' . $GLOBALS["right"] . ' 0;' .
		  '" ' .
		  'id="ddi' . $field['PostName'] . '" ' .
		  '></span></span>';*/
		 
		$result .= '</div>' . PHP_EOL;
		
		$result .= $this->DescriptionByValidation($field);

		// Todo: nbsp; here avoids the div height collapsing, probably due to something wrong in the css style
		$result .= "&nbsp;</div>" . PHP_EOL;

		return $result;
	}

	// Build a single Check Box field
	private function BuildCheckboxField($key, &$field) {
		// Todo: we don't need this check. This function is called only for required and optional items
		if (!isset($field['Display']) || !(bool) $field['Display'])
			return;

		// Here, validation will be successful, because there aren't post data, but it isn't a good right to activate che checkbox with the check
		// if (intval($this->FieldsBuilder->Fields[$index]['Value'])) $this->msg .= "checked=\"\"";
		$result = '<div class="form-field foxfield fxf-' . $key . '">' . PHP_EOL;

		$tmp = explode('|', $field['Name']);
		$inputType = count($tmp) == 1? 'checkbox' : 'radio';
		foreach ($tmp as $fieldName) {
			$fieldName = trim($fieldName);		
			
			if ($inputType == 'checkbox') {
				$checked = $field['Value'] == JText::_('JYES')? 'checked=""' : '';
			}
			else {
				$checked = $field['Value'] == $fieldName? 'checked=""' : '';
			}
			$result .=
					'<label><input ' .
					'type="' . $inputType . '" ' .
					'class="foxcheckbox" ' .
					"value=\"" . ($inputType == 'checkbox'? JText::_('JYES') : $fieldName) . "\" " .
					$checked .
					'name="' . $field['PostName'] . '" ' .
					'id="c' . $field['PostName'] . '" ' .
					'/>' . PHP_EOL;
			$result .=
					/* '<span ' .
					  'id="s' . $field['PostName'] . '" ' .
					  "onclick=\"ChangeCheckboxState('" . $field['PostName'] . "');\" " .
					  'style="background-position: ' . $GLOBALS["left"]. ' 50%;" ' .
					  '>' .
					  $this->DescriptionByValidation($field) .  // Nested span with validaton red asterisk
					  $this->AdditionalDescription($field['Display']) . // Asterisk */
					'<span>' . $fieldName . '</span></label>'; // .
			//'</span>' . PHP_EOL;
			//$result .= $this->DescriptionByValidation($field);
		}

		$result .= "<br></div>" . PHP_EOL;

		return $result;
	}

	// Build a Textarea field
	private function BuildTextareaField($key, &$field) {
		// Todo: we don't need this check. This function is called only for required and optional items
		if (!isset($field['Display']) || !(bool) $field['Display'])
			return;

		switch (intval($this->Params->get("labelsdisplay"))) {
			case 0:
				// Labels inside
				// If a value was submittet use it as text, otherwise use the field name
				$value = $field['Value'];
				$external_label = "";
				//$js = "onfocus=\"if(this.value==this.title) this.value='';\" onblur=\"if(this.value=='') this.value=this.title;\" ";
				$js = 'placeholder="' . $field['Name'] . '"';
				break;

			default:
				// Labels outside
				$value = $field['Value'];
				$external_label = $this->build_label($field);
				$js = "";
		}

		$additional_style = "";
		if (!intval($this->Params->get("labelsdisplay"))) {
			$additional_style = 'display:block;' .
					'float:none;' .
					'margin:0 auto !important;';
		}

		$result = '<div class="form-field foxfield fxf-' . $key . '">' .
				$external_label .
				"<textarea " .
				'rows="" ' .
				'cols="" ' .
				'class="' . $this->TextStyleByValidation($field) . '" ' .
				'name="' . $field['PostName'] . '" ' .
				'title="' . $field['Name'] . '" ';

		if ($field['Width'] || $field['Height']) {
			$result .= 'style="' .
					"width:" . $field['Width'] . $field['Unit'] . ' !important;' .
					"height:" . $field['Height'] . 'px' . ' !important;' . // Height in % doesn't always work
					//$additional_style .
					'" ';
		}

		$result .= $js .
				">" .
				$value . // Inner Text
				"</textarea>" .
				$this->DescriptionByValidation($field);
		$result .= "</div>" . PHP_EOL;

		return $result;
	}

	private function build_label(&$field) {
		return '<label ' .
				'style="' .
				'width:' . $this->Fields['labels']['Width'] . $this->Fields['labels']['Unit'] . ' !important;' .
				'">' .
				$field['Name'] .
				$this->AdditionalDescription($field['Display']) .
				'</label>';
	}

	// Check a single field and return a string good for html output
	function DescriptionByValidation(&$field) {
		return $field['IsValid'] ? "" : (" <span class=\"asterisk\"></span>");
	}

	// Check a single field and return a string good for html output
	function TextStyleByValidation(&$field) {
		// No post data = first time here. return a grey border
		if (!$this->Submitted)
			return "foxtext";
		// Return a green or red border
		return $field['IsValid'] ? "validfoxtext" : "invalidfoxtext";
	}

	// Check a single field and return a string good for html output
	function CheckboxStyleByValidation(&$field) {
		if (!$this->Submitted)
			return "foxcheckbox";
		// Return a green or red border
		return $field['IsValid'] ? "validcheckbox" : "invalidcheckbox";
	}

	function FieldStyleByValidation(&$field) {
		// No post data = first time here. return a grey border
		if (!$this->Submitted)
			return "defaultfoxfield";
		// Return a green or red border
		return $field['IsValid'] ? "validfoxfield" : "invalidfoxfield";
	}

	function ValidateForm() {
		$result = true;

		// Validate default fields
		$result &= $this->ValidateGroup("sender");
		// Validate Text fields
		$result &= $this->ValidateGroup("text");
		// Validate Dropdown fields
		$result &= $this->ValidateGroup("dropdown");
		// Validate Check Boxes
		$result &= $this->ValidateGroup("checkbox");
		// Validate text areas
		$result &= $this->ValidateGroup("textarea");

		return $result;
	}

	// $family can be 'text', 'dropdown', 'textarea' or 'checkbox'
	function ValidateGroup($family) {
		$result = true;

		for ($l = 0; $l < 10; ++$l) {
			// isset($this->Fields[$family . $l]) is needed to fix following error displayed when running on wamp server
			// Notice: Undefined index: sender[...] in C:\wamp\[...]\helpers\fieldsbuilder.php
			if (isset($this->Fields[$family . $l]) && $this->Fields[$family . $l]['Display']) {
				$result &= $this->Fields[$family . $l]['IsValid'];				
			}
		}

		return $result;
	}

	// Check a single field and return a boolean value
	function ValidateField($fieldvalue, $fieldtype) {
		// Params:
		// $fieldvalue is a string with the text filled by user
		// $fieldtype can be 0 = unused, 1 = optional, 2 = required
		// S | R | F | V   (Submitted | Required | Filled | Valid)
		// 0 | 0 | 0 | 1
		// 0 | 0 | 1 | 1
		// 0 | 1 | 0 | 1
		// 0 | 1 | 1 | 1
		// 1 | 0 | 0 | 1
		// 1 | 0 | 1 | 1
		// 1 | 1 | 0 | 0
		// 1 | 1 | 1 | 1
		return !($this->Submitted && ($fieldtype == 2) && empty($fieldvalue));
	}

	function ValidateEmail() {
		// data aren't destinated to this form
		//if (!count($_POST)) return true;
		if (!isset($_POST[$this->GetId()]))
			return true;

		// email field is disabled
		if (!isset($this->Fields['sender1']))
			return true;

		// email field is empty and optional
		if (empty($this->Fields['sender1']['Value']) && $this->Fields['sender1']['Display'] == 1)
			return true;

		if (!isset($this->Fields['sender1']['Value']))
			return false;

		//jimport('joomla.mail.helper');
		//(JMailHelper::isEmailAddress($email) == false)
		// Check the syntax
		//$this->Fields['sender1']['IsValid'] &= (bool)strlen(filter_var($this->Fields['sender1']['Value'], FILTER_VALIDATE_EMAIL));
		// http://www.regular-expressions.info/email.html
		$this->Fields['sender1']['IsValid'] &= (preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/',
						strtolower($this->Fields['sender1']['Value'])) == 1);

		// Check mx record
		$db = JFactory::getDBO();
		$sql = "SELECT value FROM #__" . $GLOBALS["ext_name"] . "_settings WHERE name = 'dns';";
		$db->setQuery($sql);
		$method = $db->loadResult();
		if ($method) {
			$this->$method();
		}
	}

	function dns_check() {
		// Check mx record
		if (empty($this->Fields['sender1']['Value']))
			return;

		$parts = explode("@", $this->Fields['sender1']['Value']);
		$domain = array_pop($parts);
		if (!empty($domain))
			$this->Fields['sender1']['IsValid'] &= checkdnsrr($domain, "MX");
	}

	function disabled() {
		return true;
	}

}

function sort_fields($a, $b) {
	return $a["Order"] - $b["Order"];
}

class fieldsbuilderCheckEnvironment {

	protected $InstallLog;

	public function __construct() {
		$this->InstallLog = new FLogger("fieldsbuilder", "install");
		$this->InstallLog->Write("--- Determining if this system is able to query DNS records ---");

		$value = $this->test_function("checkdnsrr");

		$db = JFactory::getDBO();
		$sql = "REPLACE INTO #__" . $GLOBALS["ext_name"] . "_settings (name, value) VALUES ('dns', '$value');";
		$db->setQuery($sql);
		$result = $db->query();

		$this->InstallLog->Write("--- Method choosen to query DNS records is [$value] ---");

		// Load global configuration
		// Access the Component-wide default parameters, already overridden with those for the menu item (if applicable):
		// $params = JComponentHelper::getParams("com_foxcontact")->toObject();
		// Access the Component-wide default parameter values, without the menu overrides:
		$params = JComponentHelper::getComponent("com_foxcontact")->params->toObject();

		// Test environment and update the configuration
		$this->test_addresses($params);

		// Save the configuration
		//$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->update($db->quoteName("#__extensions"));
		$query->set($db->quoteName("params") . " = " . $db->quote(json_encode($params)));
		// Improve the performance by referencing the record using the index element + client_id
		$query->where($db->quoteName("element") . " = " . $db->quote("com_foxcontact"));
		$query->where($db->quoteName("client_id") . " = " . $db->quote("1"));
		$db->setQuery($query);
		$result = $db->query();

		// Equivalent code
		/*
		  $table = JTable::getInstance("extension");
		  $table->load(array("element" => "com_foxcontact", "client_id" => 1));
		  $table->bind(array("params" => json_encode($config)));
		  $result = $table->check() && $table->store();
		 */

		return $result;
	}

	private function test_function($fname) {
		if (!function_exists($fname)) {
			$this->InstallLog->Write("$fname function doesn't exist.");
			return "disabled";
		}
		$this->InstallLog->Write("$fname function found. Let's see if it works.");

		// Check mx record
		$result = $fname("fox.ra.it", "MX");
		$this->InstallLog->Write("testing function [$fname]... [" . intval($result) . "]");
		return $result ? "dns_check" : "disabled";
	}

	private function test_addresses(&$params) {
		$params->adminemailfrom->select = "admin";
		$params->adminemailreplyto->select = "submitter";

		$params->submitteremailfrom->select = "admin";
		$params->submitteremailreplyto->select = "admin";

		$application = JFactory::getApplication();
		// SMTP authentication may require that the sender address is the same that the auth username
		if ($application->getCfg("mailer") == "smtp" && (bool) $application->getCfg("smtpauth") && strpos($application->getCfg("smtpuser"),
						"@") !== false) {
			$params->adminemailfrom->select = "custom";
			$params->adminemailfrom->name = $application->getCfg("fromname");
			$params->adminemailfrom->email = $application->getCfg("smtpuser");

			$params->submitteremailfrom->select = "custom";
			$params->submitteremailfrom->name = $application->getCfg("fromname");
			$params->submitteremailfrom->email = $application->getCfg("smtpuser");
		}
	}

}
