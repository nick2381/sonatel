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
	require_once($inc_dir . '/fdatapump.php');
	require_once($inc_dir . '/fsession.php');
	include_once(realpath(dirname(__FILE__) . "/../" . substr(basename(realpath(dirname(__FILE__) . "/..")), 4) . ".inc"));


	class FCaptcha extends FDataPump
	{
		protected $fsession;

		public function __construct(&$params, &$messages)
		{
			parent::__construct($params, $messages);

			$this->Name = "FCaptcha";

			// Read captcha value submitted
			$this->Fields['Value'] = $this->FaultTolerance(JRequest::getVar("fcaptcha", NULL, 'POST'));
			// Read captcha answer
			$jsession = JFactory::getSession();
			$this->fsession = new FSession($jsession->getId(), $this->Application->cid, $this->Application->mid);
			$this->Fields['Secret'] = $this->FaultTolerance($this->fsession->Load('captcha_answer'));
			/*
			// Reset captcha solution in the session after read it, avoiding that a fucked lamer
			// abuse of the *same session* without request the captcha again, to send tons of email
			$fsession->PurgeValue("captcha_answer");
			// Do not purge it any more to keep a good answer if other fields are wrong.
			// However, purge it after email sent, avoiding the lamer above use the same value to send other email
			*/
			// Check if the answer if correct
			$this->isvalid = intval($this->Validate());
		}


		protected function LoadFields()
		{
		}


		protected function LoadField($type, $number)  // Example: 'text', '0'
		{
		}


		function OverrideFields()
		{
		}


		function OverrideField($type, $number)
		{
		}


		public function Show()
		{
			if (!(bool)$this->Params->get("stdcaptchadisplay")) return "";
			$captcha_width = (int)$this->Params->get("stdcaptchawidth", "");
			$captcha_height = (int)$this->Params->get("stdcaptchaheight", "");

			$valid = (!empty($this->Fields['Secret']) && $this->Fields['Value'] == $this->Fields['Secret']);

			switch (intval($this->Params->get("labelsdisplay")))
			{
				case 0:
					// Labels inside
					// If a value was submittet use it as text, otherwise use the field name
					$value = $this->Fields['Value'] ? $this->Fields['Value'] : $this->Params->get("stdcaptcha", "");
					$external_label = "";
					$js = "onfocus=\"if(this.value==this.title) this.value='';\" onblur=\"if(this.value=='') this.value=this.title;\" ";
					break;

				default:
					// Labels outside
					$value = $this->Fields['Value'];
					$external_label = $this->build_label($field);
					$js = "";
			}

			// Center when there are no labels
			$additional_style = "";
			if (!intval($this->Params->get("labelsdisplay")))
			{
				$additional_style =
				'display:table;' .
				'float:none;' .
				'margin:0 auto 10px auto !important;';
			}

			$result =
			'<div class="foxfield fxf-captcha';
			if ($valid) $result .= 'display:none !important;';
			$result .= '">' . PHP_EOL .
			$external_label .

			'<div ' .
			'class="fcaptchacontainer" ' .
			'style="' . $additional_style . '" ' .
			'>' . PHP_EOL;

			if (!$valid)
			{
				$result .=

				// Captcha image
				'<div class="fcaptchafieldcontainer">' .
				'<img src="' . JUri::base(true) . '/index.php?option=' . $GLOBALS["com_name"] . '&amp;view=loader&amp;owner=' . $this->Application->owner . '&amp;id=' . $this->Application->oid . '&amp;type=captcha" ' .
				'class="fox_captcha_img" ' .
				'alt="captcha" ' .  // w3c validation
				'id="fcaptcha_' . $this->GetId() . '" width="' . $captcha_width . '" height="' . $captcha_height . '"/>' .
				'</div>'; // fcaptchafieldcontainer
			}

			$result .=
			// Input for answer
			'<div class="fcaptchainputcontainer">' .

			$this->DescriptionByValidation() .  // Example: *

			'<input ' .
			'class="' . $this->TextStyleByValidation() . '" ' .
			'type="text" ' .
			'name="' . "fcaptcha". '" ' .
			'style="width:' . ($captcha_width - 30) . 'px !important;" ' .
			'value="' . $value . '" ' .
			'title="' . $this->Params->get("stdcaptcha", "") . '" ' .
			$js;

			if ($valid)
			{
				$result .=
				/*'value="' . $this->Fields['Value'] . '" ' .*/
				'readonly="readonly" ';
			}

			$result .=
			'/>' .
			'</div>';  // fcaptchainputcontainer

			if (!$valid)
			{
				$result .=

				// Reload button
				'<div class="fcaptcha-reload-container">' .
				// Show a transparent dummy image
				'<img src="' . JUri::base(true) . '/media/' . $GLOBALS["com_name"] . '/images/transparent.gif" ' .
				'id="reloadbtn_' . $this->GetId() . '" ' .
				'alt="' . JTEXT::_($GLOBALS["COM_NAME"] . '_RELOAD_ALT') . '" ' .
				'title="' . JTEXT::_($GLOBALS["COM_NAME"] . '_RELOAD_TITLE') . '" ' .
				"onclick=\"javascript:ReloadFCaptcha('fcaptcha_" . $this->GetId() . "')\" />" .
				'</div>'.   // fcaptchafieldcontainer
				// Without javascript enable, you will not be able to click reload button, so let's show it only if javascript is enabled
				"<script language=\"javascript\" type=\"text/javascript\">BuildReloadButton('reloadbtn_" . $this->GetId() . "');</script>";
			}

			$result .=
			'</div>' .  // fcaptchacontainer
			'</div>' .  // Row div
			PHP_EOL;

			if (!$this->isvalid) $this->Messages[] = JText::sprintf($GLOBALS["COM_NAME"] . '_ERR_INVALID_VALUE', JText::_($GLOBALS["COM_NAME"] . '_SECURITY_CODE'));

			return $result;
		}


		private function build_label(&$field)
		{
			// Label
			return '<label ' .
			'style="' .
			'width:' . $this->Params->get('labelswidth') . $this->Params->get('labelsunit') . ' !important;' .
			'">' .
			// Unlike other fields, captcha can have an empty description
			// "&nbsp;" default value avoids a misaligned visualization
			$this->Params->get("stdcaptcha", "&nbsp;") .
			'</label>' . PHP_EOL;
		}

		// Check a single field and return a boolean value
		function Validate()
		{
			//$isrequired = ($this->Fields['Display']);
			$isrequired = (bool)$this->Params->get("stdcaptchadisplay");

			// Value == Secret == NULL is not a valid condition
			$this->isvalid = (!empty($this->Fields['Secret']) && $this->Fields['Value'] == $this->Fields['Secret']);
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
			// $this->isvalid now stores the state of the uploaded file only...
			return !($this->Submitted && $isrequired && !$this->isvalid);
			// ..but after returning it will consider the submitted and required state too
		}


		private function TextStyleByValidation()
		{
			/*
			// No post data = first time here
			// Field is valid = we can't confuse the user telling he gave a wrong answer
			// In both cases, return a grey border
			if (!$this->Submitted || $this->isvalid) return "foxtext";

			// Form submitted and wrong captcha answer
			return "invalidfoxtext";
			*/
			// No post data = first time here. return a grey border
			if (!$this->Submitted) return "foxtext";
			// Return a green or red border
			return $this->isvalid ? "validfoxtext" : "invalidfoxtext";

		}

		private function DescriptionByValidation()
		{
			return $this->isvalid ? "" : (" <span class=\"asterisk\"></span>");
		}


		private function FaultTolerance($string)
		{
			// Same content as the label
			if ($string == $this->Params->get("stdcaptcha", "")) return $string;

			// Convert in lower case
			$string = strtolower($string);
			// correct common mistakes
			$string = preg_replace("/[l1]/", "i", $string);   // I i l 1 -> i
			$string = preg_replace("/[0]/", "o", $string);   // O o 0 -> o
			$string = preg_replace("/[q9]/", "g", $string);   // g q 9 -> g
			$string = preg_replace("/[5]/", "s", $string);   // S s 5 -> s
			$string = preg_replace("/[8]/", "b", $string);   // B 8 -> b

			return $string;
		}

	}


	class fcaptchaCheckEnvironment
	{
		protected $InstallLog;

		public function __construct()
		{
			$this->InstallLog = new FLogger("fcaptchaimage", "install");
			$this->InstallLog->Write("--- Determining if this system is able to draw captcha images ---");

			switch (true)
			{
				case $this->gd_usable(): $value = "use_gd"; break;
					// No way to draw images
				default: $value = "disabled";
			}

			$db = JFactory::getDBO();
			$sql = "REPLACE INTO #__" . $GLOBALS["ext_name"] . "_settings (name, value) VALUES ('captchadrawer', '$value');";
			$db->setQuery($sql);
			$result = $db->query();

			$this->InstallLog->Write("--- Method choosen to draw captcha images is [$value] ---");
			return $result;
		}


		private function gd_usable()
		{
			if (!extension_loaded("gd") || !function_exists("gd_info"))
			{
				$this->InstallLog->Write("gd extension not found");
				return false;
			}

			$this->InstallLog->Write("gd extension found. Let's see if it works.");

			$gdinfo = gd_info();
			foreach ($gdinfo as $key => $line) $this->InstallLog->Write($key . "... [" . $line . "]");

			$result = true;
			$result &= $this->testfunction("imagecreate");
			$result &= $this->testfunction("imagecolorallocate");
			$result &= $this->testfunction("imagefill");
			$result &= $this->testfunction("imageline");
			$result &= $this->testfunction("imagettftext");
			$result &= $this->testfunction("imagejpeg");
			$result &= $this->testfunction("imagedestroy");

			return $result;
		}


		private function testfunction($function)
		{
			$result = function_exists($function);
			$this->InstallLog->Write("testing function [$function]... [" . intval($result) . "]");
			return $result;
		}

	}
?>
