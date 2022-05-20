<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

$helpdir = JPATH_BASE . '/components/' . $GLOBALS["com_name"] . '/helpers/';
require_once($helpdir . "fsubmitter.php");
require_once($helpdir . "fieldsbuilder.php");
require_once($helpdir . "fajaxuploader.php");
require_once($helpdir . "fuploader.php");
require_once($helpdir . "fadminmailer.php");
require_once($helpdir . "fsubmittermailer.php");
require_once($helpdir . "fantispam.php");
require_once($helpdir . "fcaptcha.php");
require_once($helpdir . "fjmessenger.php");
require_once($helpdir . "fnewsletter.php");
require_once($helpdir . "acymailing.php");
require_once($helpdir . "jnews.php");
require_once($helpdir . "fsession.php");

require_once JPATH_COMPONENT . "/lib/functions.php";

class FoxContactViewFoxContact extends JView {

	protected $Application;
	protected $cparams;
	protected $Submitter;
	protected $FieldsBuilder;
	protected $AjaxUploader;
	protected $Uploader;
	protected $Antispam;
	protected $JMessenger;
	protected $AdminMailer;
	protected $SubmitterMailer;
	protected $Newsletter;
	protected $AcyMailing;
	protected $JNews;
	protected $FoxCaptcha;
	protected $messages = array();
	public $FormText = "";

	protected function getFieldsText() {
		$result = '';
		foreach ($this->FieldsBuilder->Fields as $key => $field) {
			switch ($field['Type']) {
				case 'sender':
				case 'text':
				case 'textarea':
				case 'dropdown':
				case 'checkbox':
					if ($field['Display']) {
						$result .= '[' . JFilterInput::getInstance()->clean($field['Name'], '') . '] ' .
								JFilterInput::getInstance()->clean($field['Value'], '') . '<br>';
					}
			}
		}

		// a blank line
		$result .= PHP_EOL;
		return $result;
	}

	protected function processOrderData() {
		$db = JFactory::getDbo();

		$categoryId = getCategoryByType('order');
		$state = 0;
		$access = 1;
		$language = 'ru-RU';

		$description = $this->getFieldsText();
		$title = 'Заявка от ' . $this->FieldsBuilder->Fields['sender0']['Value'];

		$orderSum = 0;
		$start = $this->FieldsBuilder->Fields['text0']['Value'];
		$end = $this->FieldsBuilder->Fields['text1']['Value'];

		// calculate order sum
		$tmp = explode('.', $start);
		$start = $tmp[2] . '-' . $tmp[1] . '-' . $tmp[0];
		$tmp = explode('.', $end);
		$end = $tmp[2] . '-' . $tmp[1] . '-' . $tmp[0];

		// get prices
		$query = 'select * from #__content '
				. 'where catid = ' . getCategoryByType('product') .
				' and title = ' . $db->quote($this->FieldsBuilder->Fields['text4']['Value']);
		$db->setQuery($query);
		$article = $db->loadObject();
		$query = 'select * from #__simplebooking
			where article_id = ' . (int) $article->id . ' and '
				. ' date >= ' . $db->quote($start) . ' and date < ' . $db->quote($end);
		$attribs = json_decode($article->attribs);
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$dayPrices = array();
		foreach ($rows as $row) {
			$dayPrices[$row->date] = $row;
		}
		$priceName = 'price' . (int) $this->FieldsBuilder->Fields['dropdown0']['Value'];
		//error_log($priceName);

		// calculate sum
		$description .= '<table><tr><th>Дата<th>Цена</tr>';
		$endDate = $date = date_create($end);
		for ($date = date_create($start); $date < $endDate; date_add($date,
						date_interval_create_from_date_string('1 days'))) {
			$sdate = date_format($date, 'Y-m-d');
			if (isset($dayPrices[$sdate]) && !empty($dayPrices[$sdate]->$priceName)) {
				$price = $dayPrices[$sdate]->$priceName;
				//error_log(print_r($dayPrices[$sdate], true));
			}
			else {
				$price = $attribs->attr_product_price;
				//error_log($sdate . ': ' . $attribs->attr_product_price);
			}
			$orderSum += $price;
			$description .= '<tr><td>' . $sdate . '<td>' . $price . '</tr>';
		}
		$description .= '</table>';

		$attribs = json_encode(array(
			'attr_order_sum' => $orderSum,
			'attr_order_items' => (int) $article->id,
			'attr_order_email' => $this->FieldsBuilder->Fields['sender1']['Value'],
			'attr_order_contact' => $this->FieldsBuilder->Fields['sender0']['Value'],
			'attr_order_startdate' => $this->FieldsBuilder->Fields['text0']['Value'],
			'attr_order_enddate' => $this->FieldsBuilder->Fields['text1']['Value'],
			'attr_order_num' => $this->FieldsBuilder->Fields['dropdown0']['Value']
		));

		$query = 'insert into #__content (
			`title`, `introtext`, `attribs`,
			`state`, `catid`, `created`, `access`, `language`
		)
    values (' .
				$db->quote($title) . ',' .
				$db->quote($description) . ',' .
				$db->quote($attribs) . ',' .
				$db->quote($state) . ',' .
				$db->quote($categoryId) . ',' .
				'NOW(),' .
				$db->quote($access) . ',' .
				$db->quote($language) . '
		)';
		$db->setQuery($query);
		$res = $db->query();
		if ($res) {
			$this->Submitter->Params->set('email_subject',
					$this->Submitter->Params->get('email_subject') . ' ' . $db->insertid() .
					' от ' . $this->FieldsBuilder->Fields['sender0']['Value']
			);

			return true;
		}

		return false;
	}

	protected function processFeedbackData() {
		/*
		  if (strpos($this->cparams->get('pageclass_sfx'), 'some-type-page') !== false) {
		  //...
		  }
		 */

		if (strpos($this->cparams->get('pageclass_sfx'), 'booking-order-page') !== false) {
			return $this->processOrderData();
		}

		return true;
	}

	// Overwriting JView display method
	function display($tpl = null) {
		$this->Application = JFactory::getApplication();
		// Access the Component-wide default parameters, already overridden with those for the menu item (if applicable):
		$this->cparams = $this->Application->getParams('com_foxcontact');

		// Add a stylesheet
		$document = JFactory::getDocument();
		$document->addStyleSheet(JUri::base(true) . '/components/' . $this->Application->scope . "/css/" . $this->cparams->get("stylesheet",
						"neon.css"));

		$this->Submitter = new FSubmitter($this->cparams, $this->messages);
		$this->FieldsBuilder = new FieldsBuilder($this->cparams, $this->messages);
		$this->AjaxUploader = new FAjaxUploader($this->cparams, $this->messages);
		$this->Uploader = new FUploader($this->cparams, $this->messages);
		$this->FoxCaptcha = new FCaptcha($this->cparams, $this->messages);
		$this->JMessenger = new FJMessenger($this->cparams, $this->messages, $this->FieldsBuilder);
		$this->Antispam = new FAntispam($this->cparams, $this->messages, $this->FieldsBuilder);
		$this->Newsletter = new FNewsletter($this->cparams, $this->messages, $this->FieldsBuilder);
		$this->AcyMailing = new FAcyMailing($this->cparams, $this->messages, $this->FieldsBuilder);
		$this->JNews = new FJNewsSubscriber($this->cparams, $this->messages, $this->FieldsBuilder);
		$this->AdminMailer = new FAdminMailer($this->cparams, $this->messages, $this->FieldsBuilder);
		$this->SubmitterMailer = new FSubmitterMailer($this->cparams, $this->messages,
				$this->FieldsBuilder);

		$this->FormText .= $this->FieldsBuilder->Show();
		$this->FormText .= $this->AjaxUploader->Show();
		$this->FormText .= $this->AcyMailing->Show();
		$this->FormText .= $this->JNews->Show();
		$this->FormText .= $this->FoxCaptcha->Show();
		$this->FormText .= $this->Antispam->Show();
		$this->FormText .= $this->Submitter->Show();

		switch (0) {
			case $this->Submitter->IsValid(): break;
			case $this->FieldsBuilder->IsValid(): break;
			case $this->AjaxUploader->IsValid(): break;
			case $this->Uploader->IsValid(): break;
			case $this->FoxCaptcha->IsValid(): break;
			case $this->Antispam->IsValid(): break;

			case $this->processFeedbackData(): break;
			case $this->JMessenger->Process(): break;
			case $this->Newsletter->Process(): break;
			case $this->AcyMailing->Process(): break;
			case $this->JNews->Process(): break;
			case $this->AdminMailer->Process(): break;
			case $this->SubmitterMailer->Process(): break;
			default: // None of the previous checks are failed
				// Avoid to show the Form and the button again
				$this->FormText = "";

				// Reset captcha solution in the session after read it, avoiding that lamers
				// abuse of the *same session* without request the captcha again, to send tons of email
				$jsession = JFactory::getSession();
				$fsession = new FSession($jsession->getId(), $this->Application->cid, $this->Application->mid);
				$fsession->PurgeValue("captcha_answer");

				HeaderRedirect($this->cparams);
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			$this->Application->enqueueMessage(implode('<br />', $errors), 'error');
			//return false;
		}

		// Display the view
		parent::display($tpl);
	}

}
