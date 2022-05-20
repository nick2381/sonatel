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

require_once JPATH_COMPONENT . "/helpers/fsession.php";
require_once JPATH_COMPONENT . "/helpers/flogger.php";
require_once JPATH_COMPONENT . "/helpers/fmimetype.php";
require_once "loader.php";

define('KB', 1024);

class uploaderLoader extends Loader
{
	protected function type()
	{
		return "uploader";
	}

	protected function http_headers()
	{
	}

	protected function content_header()
	{
	}

	protected function content_footer()
	{
	}

	protected function load()
	{

		switch (true)
		{
			case isset($_GET['qqfile']): $um = new XhrUploadManager(); break;
			case isset($_FILES['qqfile']): $um = new FileFormUploadManager(); break;
			default:
				// Malformed / malicious request, or attachment exceeds server limits
				$result = array('error' => JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_NO_FILE'));
				exit(htmlspecialchars(json_encode($result), ENT_NOQUOTES));
		}
		$um->Params = &$this->Params;
		$result = $um->HandleUpload(JPATH_COMPONENT . '/uploads/');
		// to pass data through iframe you will need to encode all html tags
		echo(htmlspecialchars(json_encode($result), ENT_NOQUOTES));

	}
}





abstract class FUploadManager
{
	protected $Session;
	protected $Log;
	protected $DebugLog;

	abstract protected function save_file($path);
	abstract protected function get_file_name();
	abstract protected function get_file_size();


	function __construct()
	{
		$this->Log = new FLogger();
		$this->DebugLog = new FDebugLogger("file uploader");

		$this->Session = JFactory::getSession();
	}


	public function HandleUpload($uploadDirectory)
	{
		$this->DebugLog->Write("HandleUpload() started");

		if (!is_writable($uploadDirectory))
		{
			$this->DebugLog->Write("Directory " . $uploadDirectory . " is not writable");
			return array('error' => JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_DIR_NOT_WRITABLE'));
		}
		$this->DebugLog->Write("Directory " . $uploadDirectory . " is ok");

		// Check file size
		$size = $this->get_file_size();
		if ($size == 0)  // It must be > 0
		{
			$this->DebugLog->Write("File size is 0");
			return array('error' => JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_FILE_EMPTY'));
		}
		$this->DebugLog->Write("File size is > 0");

		// uploadmax_file_size defaults to 0 to prevent hack attempts
		$max = $this->Params->get("uploadmax_file_size", 0) * KB;  // and < max limit
		if ($size > $max)
		{
			$this->DebugLog->Write("File size too large ($size > $max)");
			return array('error' => JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_FILE_TOO_LARGE'));
		}
		$this->DebugLog->Write("File size ($size / $max) is ok");

		// Clean file name
		$filename = preg_replace("/[^\w\.-_]/", "_", $this->get_file_name());
		// Assign a random unique id to the file name, to avoid that lamers can force the server to execute their uploaded shit
		$filename = uniqid() . "-" . $filename;
		$full_filename = $uploadDirectory . $filename;

		if (!$this->save_file($full_filename))
		{
			$this->DebugLog->Write("Error saving file");
			return array('error'=> JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_SAVE_FILE'));
		}
		$this->DebugLog->Write("File saved");

		$mimetype = new FMimeType();
		if (!$mimetype->Check($full_filename, $this->Params))
		{
			// Delete the file uploaded
			unlink($full_filename);
			$this->DebugLog->Write("File type [" . $mimetype->Mimetype . "] is not allowed. Allowed types are:" . PHP_EOL . print_r($mimetype->Allowed, true));
			return array('error' => JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_MIME') . " [" . $mimetype->Mimetype . "]");
		}
		$this->DebugLog->Write("File type [" . $mimetype->Mimetype . "] is allowed");

		$cid = JRequest::getVar("cid", NULL, 'GET');
		$mid = JRequest::getVar("mid", NULL, 'GET');
		$owner = JRequest::getVar("owner", NULL, "GET");
		$id = JRequest::getVar("id", NULL, "GET");
		$jsession = JFactory::getSession();
		$fsession = new FSession($jsession->getId(), $cid, $mid);

		// Store the answer in the session
		$data = $fsession->Load('filelist');  // Read the list from the session
		if ($data) $filelist = explode("|", $data);
		else $filelist = array();
		$filelist[] = $filename; // Append this file to the list
		$data = implode("|", $filelist);
		$fsession->Save($data, "filelist");

		$this->Log->Write("File " . $filename . " uploaded succesful.");
		$this->DebugLog->Write("File uploaded succesful.");
		return array("success" => true);
	}


}


// File uploads via XMLHttpRequest
class XhrUploadManager extends FUploadManager
{

	public function __construct()
	{
		parent::__construct();
	}


	protected function save_file($path)
	{
		$input = fopen("php://input", "r");
		$target = fopen($path, "w");

		// Todo: Check they are both valid strams before using them
		$realSize = stream_copy_to_stream($input, $target);

		fclose($input);
		fclose($target);

		return ($realSize == $this->get_file_size());
	}


	protected function get_file_name()
	{
		// Todo: usare il wrapper di Joomla per le get
		return $_GET['qqfile'];
	}


	protected function get_file_size()
	{
		if (isset($_SERVER["CONTENT_LENGTH"])) return (int)$_SERVER["CONTENT_LENGTH"];
		//else throw new Exception('Getting content length is not supported.');
		return 0;
	}

}


// File uploads via regular form post (uses the $_FILES array)
class FileFormUploadManager extends FUploadManager
{
	public function __construct()
	{
		parent::__construct();
	}


	protected function save_file($path)
	{
		return move_uploaded_file($_FILES['qqfile']['tmp_name'], $path);
	}


	protected function get_file_name()
	{
		return $_FILES['qqfile']['name'];
	}

	protected function get_file_size()
	{
		return $_FILES['qqfile']['size'];
	}

}

