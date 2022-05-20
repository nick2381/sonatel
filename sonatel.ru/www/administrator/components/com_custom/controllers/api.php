<?php

class CustomControllerApi extends JControllerLegacy {

	function __construct($default = array()) {
		parent::__construct($default);

		//...
	}

	//
	// ajax actions
	//

	protected function getCustomTypeById($categoryId) {
		$db = JFactory::getDbo();
		$types = getCustomTypes();

		if (isset($types['categories'][$categoryId])) {
			$typeName = $types['categories'][$categoryId];
			return $typeName;
		}

		// check parent id
		$query = 'select parent_id from #__categories
			where id = ' . (int) $categoryId;
		$db->setQuery($query);
		$row = $db->loadObject();
		if ($row && $row->parent_id) {
			return $this->getCustomTypeById($row->parent_id);
		}

		return '';
	}

	function customtype() {
		$categoryId = JRequest::getVar('id');

		echo json_encode($this->getCustomTypeById($categoryId));
		jexit();
	}

	function save_dates() {
		$db = JFactory::getDbo();
		$rows = json_decode(JRequest::getVar('data'));
		$apartId = JRequest::getInt('apart_id');
		$result = false;

		if ($apartId) {
			try {
				$db->transactionStart();
				foreach ($rows as $row) {
					$query = 'insert into #__simplebooking
						(article_id, date, ordered, price1, price2, price3, price4)
						values (' .
							$db->quote($apartId) . ', ' .
							$db->quote($row->date) . ', ' .
							$db->quote($row->ordered) . ', ' .
							$db->quote($row->price1) . ', ' .
							$db->quote($row->price2) . ', ' .
							$db->quote($row->price3) . ', ' .
							$db->quote($row->price4) .
							') on duplicate key update '
							. 'ordered = ' . $db->quote($row->ordered) . ', '
							. 'price1 = ' . $db->quote($row->price1) . ', '
							. 'price2 = ' . $db->quote($row->price2) . ', '
							. 'price3 = ' . $db->quote($row->price3) . ', '
							. 'price4 = ' . $db->quote($row->price4);

					$db->setQuery($query);
					$res = $db->execute();
				}
				$db->transactionCommit();
				$result = true;
			}
			catch (Exception $e) {
				$db->transactionRollback();
				$result = false;
			}
		}

		echo json_encode(array(
			'result' => $result
		));
		jexit();
	}

	function renderPaypalForm($item) {
		$total = number_format($item->amount, 2, '.', '');
		$half = number_format($item->amount / 2, 2, '.', '');

		/*
		  $url = 'https://www.paypal.com/cgi-bin/webscr?' .
		  http_build_query(array(
		  'cmd' => '_xclick',
		  'business' => $item->sellerEmail,
		  'item_name' => $item->title,
		  'item_number' => $item->id,
		  'amount' => $half,
		  'no_shipping' => 1,
		  'currency_code' => 'RUB'
		  ));
		 */
		$url = 'http://' . $_SERVER['HTTP_HOST'] . '/checkout.html?' .
				http_build_query(array(
					'n' => $item->id,
					't' => $item->publicId
		));
		?>
		<h3>Заявка на бронирование № <?= $item->id ?></h3>

		<div><p>Сумма: <?= $total ?> руб.</p></div>
		<div><p>Предоплата: <?= $half ?> руб.</p></div>

		<?php /* ?>
		  <div>
		  <a href="<?= $url ?>" title="Перейти к оплате"><img
		  alt="Перейти к оплате"
		  src="<?= $_SERVER['HTTP_HOST'] ?>/images/SunriseBtn_checkout_w_PP_Russian_295X43.png"/></a>
		  <?php
		  <a href="<?= $url ?>" title="Оформить заказ"><img
		  alt="Оформить заказ"
		  src="<?= $_SERVER['HTTP_HOST'] ?>/images/EC_btn_ru_283x62.jpg"/></a>
		  </div>
		 */ ?>
		<hr>
		<div>
			<?php /* ?>Перейти к оплате: <?= $url ?> <? */ ?>
			Оформить заказ: <?= $url ?>
		</div>
		<?php
		/*
		  ?>
		  <form method="post" action= "https://www.paypal.com/cgi-bin/webscr">
		  <input type="hidden" name="cmd" value="_xclick">
		  <input type="hidden" name="business" value="<?= $item->sellerEmail ?>">
		  <input type="hidden" name="item_name" value="<?= $item->title ?>">
		  <input type="hidden" name="item_number" value="<?= $item->id ?>">
		  <input type="hidden" name="amount" value="<?= $half ?>">
		  <input type="hidden" name="no_shipping" value="1">
		  <input name="currency_code" type="hidden" value="RUB" />

		  <h3>Заявка на бронирование № <?= $item->id ?></h3>

		  <div><p>Сумма: <?= $total ?> руб.</p></div>
		  <div><p>Предоплата: <?= $half ?> руб.</p></div>
		  <div><p><input type="submit" value="Перейти к оплате"></p>
		  <img src="<?= $_SERVER['HTTP_HOST'] ?>/images/RU_bdg_payments_by_pp_2ln.png"/>
		  </div>
		  <hr>
		  <div><?= $item->introtext ?></div>
		  </form>
		  <?php
		 */
	}

	function send_confirmation() {
		$db = JFactory::getDbo();
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();

		$mailer->setSender(array(
			$config->getValue('config.mailfrom'),
			$config->getValue('config.fromname')
		));

		$id = JRequest::getInt('id');
		$item = bookingGetOrder($id);
		$item->publicId = bookingGetOrderHash($item);

		$mailer->addRecipient(array($item->email));

		$mailer->isHTML(true);
		$mailer->setSubject($item->contact . ', ваша заявка подтверждена ');

		ob_start();
		$this->renderPaypalForm($item);
		$body = ob_get_contents();
		ob_end_clean();
		$mailer->setBody($body);

		echo $mailer->Send() ? '1' : '0';

		jexit();
	}

}
