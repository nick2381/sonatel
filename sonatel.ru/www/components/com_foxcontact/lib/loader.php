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

abstract class Loader
{
	abstract protected function type();
	abstract protected function http_headers();
	abstract protected function content_header();
	abstract protected function content_footer();


	public function Show()
	{
		$this->headers();
		$this->http_headers();
		$this->content_header();
		$this->load();
		$this->content_footer();

		//die();
		JFactory::getApplication()->close();
	}


	private function headers()
	{
		// Prepare some useful headers
		header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		// must not be cached by the client browser or any proxy
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}


	protected function load()
	{
		// Complete the script name with its path
		$filename = JRequest::getVar("filename", "", "GET");
		// Only admit lowercase a-z, underscore and minus. Forbid numbers, symbols, slashes and other stuff.
		// For your security, *don't* touch the following regular expression.
		preg_match('/^[a-z_-]+$/', $filename) or $filename = "invalid";
		$local_name = realpath(dirname(__FILE__) . "/../" . $this->type() . "/" . $filename . "." . $this->type());

		require_once $local_name;
	}

}

