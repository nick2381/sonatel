<?php

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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * FoxContact Component Controller
 */
class FoxContactController extends JController {

	public function display($cachable = false, $urlparams = false) {
		$application = JFactory::getApplication("site");
		$menu = $application->getMenu();
		$activemenu = $menu->getActive();
		$view = JRequest::getCmd("view", $this->default_view);

		// When called the form without a valid menu id, hijack to the invalid view
		if ($view == "foxcontact" && !$activemenu) {
			$_GET["view"] = "invalid";
			$_REQUEST["view"] = "invalid";
			$GLOBALS['_JREQUEST']["view"]["DEFAULTCMD0"] = "invalid";
		}

		return parent::display($cachable = false, $urlparams = false);
	}

	private function getModuleParams($id) {
		$db = JFactory::getDbo();
		jimport("joomla.database.databasequery");
		$query = $db->getQuery(true);
		$query->select('`params`');
		$query->from('`#__modules`');
		$query->where("`id` = " . intval($id));
		$query->where("`module` = 'mod_foxcontact'");
		$db->setQuery($query);

		// Load parameters from database
		$json = $db->loadResult();
		// Convert to JRegistry
		$params = new JRegistry($json);

		return $params;
	}

	public function requestIndividual() {
		$db = JFactory::getDbo();
		$config = JFactory::getConfig();

		$helpdir = JPATH_BASE . '/components/' . $GLOBALS['com_name'] . '/helpers/';
		require $helpdir . 'fcaptcha.php';

		/*
		  require dirname(__FILE__) . '/../../vendor/securimage/securimage.php';

		  $captcha = new Securimage();
		  $captcha->ttf_file = dirname(__FILE__) . '/ahronbd.ttf';
		  $captcha->code_length = 5;
		  $captcha->use_wordlist = false;
		  $captcha->perturbation = 0.3;
		  //$captcha->image_bg_color  = new Securimage_Color("#0099CC");
		  $captcha->text_color = new Securimage_Color("#0A0A0A");
		  $captcha->num_lines = 4;
		  $captcha->line_color = new Securimage_Color("#0099CC");
		  $captcha->case_sensitive  = false;

		  $res = $captcha->check($_POST['captcha']);
		 */

		// Read captcha answer
		$cid = 0;
		$mid = 102;
		$app = JFactory::getApplication();
		$captchaValue = JRequest::getVar('captcha', '');
		$jsession = JFactory::getSession();
		$query = 'select * from #__foxcontact_sessions '
				. 'where id = ' . $db->quote($jsession->getId()) . ' and cid = ' . (int) $cid . ' and mid = ' . (int) $mid;
		$db->setQuery($query);
		$row = $db->loadObject();
		$secret = $row->data;

		$res = !empty($secret) && strtolower($captchaValue) == strtolower($secret);
		if (!$res) {
			echo 'error:Неправильное контрольное значение.';
			jexit();
		}

		$params = $this->getModuleParams($mid);
		$attachment = null;
		if (!empty($_FILES['copy_document'])) {
			if ($_FILES['copy_document']['error'] != 0) {
				echo 'error:Не удалось загрузить файл.';
				jexit();
			}
			$attachment = JPATH_ROOT . '/tmp/' .
					basename($_FILES['copy_document']['tmp_name']) . '_' . $_FILES['copy_document']['name'];
			if (!move_uploaded_file($_FILES['copy_document']['tmp_name'], $attachment)) {
				echo 'error:Не удалось загрузить файл.';
				jexit();
			}
		}
		$res = JUtility::sendMail(
						$config->getValue('config.mailfrom'), $config->getValue('config.fromname'),
						$params->get('to_address'), $params->get('email_subject'),
						stripslashes($_POST['form_document']), true, null, null, $attachment
		);
		if ($attachment) {
			unlink($attachment);
		}
		
		if ($res) {
			echo 'ok';
		}
		jexit();
	}

	public function requestLegalEntity() {
		$db = JFactory::getDbo();
		$config = JFactory::getConfig();

		$helpdir = JPATH_BASE . '/components/' . $GLOBALS['com_name'] . '/helpers/';
		require $helpdir . 'fcaptcha.php';

		/*
		  require dirname(__FILE__) . '/../../vendor/securimage/securimage.php';

		  $captcha = new Securimage();
		  $captcha->ttf_file = dirname(__FILE__) . '/ahronbd.ttf';
		  $captcha->code_length = 5;
		  $captcha->use_wordlist = false;
		  $captcha->perturbation = 0.3;
		  //$captcha->image_bg_color  = new Securimage_Color("#0099CC");
		  $captcha->text_color = new Securimage_Color("#0A0A0A");
		  $captcha->num_lines = 4;
		  $captcha->line_color = new Securimage_Color("#0099CC");
		  $captcha->case_sensitive  = false;

		  $res = $captcha->check($_POST['captcha']);
		 */

		// Read captcha answer
		$cid = 0;
		$mid = 138;
		$app = JFactory::getApplication();
		$captchaValue = JRequest::getVar('captcha', '');
		$jsession = JFactory::getSession();
		$query = 'select * from #__foxcontact_sessions '
				. 'where id = ' . $db->quote($jsession->getId()) . ' and cid = ' . (int) $cid . ' and mid = ' . (int) $mid;
		$db->setQuery($query);
		$row = $db->loadObject();
		$secret = $row->data;

		$res = !empty($secret) && strtolower($captchaValue) == strtolower($secret);
		if (!$res) {
			echo 'error:Неправильное контрольное значение.';
			jexit();
		}

		$params = $this->getModuleParams($mid);
		$attachment = null;
		if (!empty($_FILES['copy_document'])) {
			if ($_FILES['copy_document']['error'] != 0) {
				echo 'error:Не удалось загрузить файл.';
				jexit();
			}
			$attachment = JPATH_ROOT . '/tmp/' .
					basename($_FILES['copy_document']['tmp_name']) . '_' . $_FILES['copy_document']['name'];
			if (!move_uploaded_file($_FILES['copy_document']['tmp_name'], $attachment)) {
				echo 'error:Не удалось загрузить файл.';
				jexit();
			}
		}
		$res = JUtility::sendMail(
						$config->getValue('config.mailfrom'), $config->getValue('config.fromname'),
						$params->get('to_address'), $params->get('email_subject'),
						stripslashes($_POST['form_document']), true, null, null, $attachment
		);
		if ($attachment) {
			unlink($attachment);
		}
		
		if ($res) {
			echo 'ok';
		}
		jexit();
	}

	/*
	  public function captcha() {
	  require dirname(__FILE__) . '/../../vendor/securimage/securimage.php';

	  $img = new Securimage();

	  // You can customize the image by making changes below, some examples are included - remove the "//" to uncomment
	  //$img->ttf_file        = './Quiff.ttf';
	  //$img->captcha_type    = Securimage::SI_CAPTCHA_MATHEMATIC; // show a simple math problem instead of text
	  //$img->case_sensitive  = true;                              // true to use case sensitve codes - not recommended
	  //$img->image_height    = 90;                                // height in pixels of the image
	  //$img->image_width     = $img->image_height * M_E;          // a good formula for image size based on the height
	  //$img->perturbation    = .75;                               // 1.0 = high distortion, higher numbers = more distortion
	  //$img->image_bg_color  = new Securimage_Color("#0099CC");   // image background color
	  //$img->text_color      = new Securimage_Color("#EAEAEA");   // captcha text color
	  //$img->num_lines       = 8;                                 // how many lines to draw over the image
	  //$img->line_color      = new Securimage_Color("#0000CC");   // color of lines over the image
	  //$img->image_type      = SI_IMAGE_JPEG;                     // render as a jpeg image
	  //$img->signature_color = new Securimage_Color(rand(0, 64),
	  //                                             rand(64, 128),
	  //                                             rand(128, 255));  // random signature color
	  // see securimage.php for more options that can be set
	  // set namespace if supplied to script via HTTP GET
	  if (!empty($_GET['namespace']))
	  $img->setNamespace($_GET['namespace']);


	  //$img->show();  // outputs the image and content headers to the browser
	  // alternate use:
	  // $img->show('/path/to/background_image.jpg');

	  $captcha = new Securimage();
	  $captcha->ttf_file = dirname(__FILE__) . '/ahronbd.ttf';
	  $captcha->code_length = 5;
	  $captcha->use_wordlist = false;
	  $captcha->perturbation = 0.3;
	  //$captcha->image_bg_color  = new Securimage_Color("#0099CC");
	  $captcha->text_color = new Securimage_Color("#0A0A0A");
	  $captcha->num_lines = 4;
	  $captcha->line_color = new Securimage_Color("#0099CC");
	  $captcha->case_sensitive  = false;

	  $captcha->show();
	  }
	 */
}
