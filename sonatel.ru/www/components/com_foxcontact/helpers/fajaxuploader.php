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

	class FAjaxUploader extends FDataPump
	{
		public function __construct(&$params, &$messages)
		{
			parent::__construct($params, $messages);

			$this->Name = "FAjaxFilePump";
			$this->isvalid = true;
		}


		protected function LoadFields()
		{
			// Nothing to load for the moment
		}


		// Build a multiple upload field
		public function Show()
		{
			// Load into <head> needed js only once and only if upload feature is enabled
			if (!(bool)$this->Params->get("uploaddisplay")) return "";
			$id = $this->GetId();
			//$cid = ((bool)$this->Application->mid) ? 0 : $this->GetComponentId();
			$action = JUri::base(true) . "/index.php?option=" . $GLOBALS["com_name"] . "&amp;view=loader&amp;type=uploader&amp;owner=" . $this->Application->owner .  "&amp;id=" . $this->Application->oid;

			$result =
			// Open row container
			'<div class="foxfield">' .
			// Label
			'<label>' .
			$this->Params->get('upload') . ". " .
			JTEXT::_($GLOBALS["COM_NAME"] . '_FILE_SIZE_LIMIT') . " " . $this->human_readable($this->Params->get("uploadmax_file_size") * 1024) .
			'</label>' .

			// Upload button and list container
			'<div id="foxupload_' . $id . '" ' .
			//'style="float:' . $GLOBALS["left"] . '"' .
			'></div>' . PHP_EOL .
			"<script language=\"javascript\" type=\"text/javascript\">CreateUploadButton('foxupload_$id', '$action', " . $this->Application->cid . ", " . $this->Application->mid . ", '" . $this->Application->owner . "', " . $this->Application->oid . ");</script>" .

			// for browsers without javascript support only
			'<noscript>' .
			// Standard file input
			'<input ' .
			'type="file" ' .
			// id raise a w3c error in case of more contact form in the same page: ID "foxstdupload" already defined
			//			'id="foxstdupload" ' .
			'name="foxstdupload"' .
			" />" .
			'</noscript>';

			$jsession = JFactory::getSession();
			$fsession = new FSession($jsession->getId(), $this->Application->cid, $this->Application->mid);
			$data = $fsession->Load('filelist');  // Read the list from the session
			if ($data) $filelist = explode("|", $data);
			else $filelist = array();

			if (count($filelist))
			{
				// Previuosly completed uploads
				$result .= '<ul class="qq-upload-list">';
				foreach ($filelist as &$file)
				{
					$result .=
					'<li class="qq-upload-success">' .
					'<span class="qq-upload-file">' . $this->format_filename(substr($file, 14)) . '</span>' .
					'<span class="qq-upload-success-text">' . JTEXT::_($GLOBALS["COM_NAME"] . '_SUCCESS') . '</span>' .
					'</li>';
				}
				$result .= '</ul>' . PHP_EOL;
			}

			// Close row container
			$result .= "</div>". PHP_EOL;
			return $result;
		}


		protected function human_readable($value)
		{
			for ($i = 0; $value >= 1000; ++$i) $value /= 1024;
			$powers = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
			return round($value, 1) . " " . $powers[$i];
		}

		protected function format_filename($value)
		{
			if (strlen($value) > 33)
			{
				$value = substr($value, 0, 19) . '...' . substr($value, -13);
			}
			return $value;
		}

	}
?>
