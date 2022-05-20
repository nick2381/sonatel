<?php

function bookingGetOrderHash($item) {
	return md5($item->id . ' ' . $item->introtext);
}

function bookingGetOrder($id) {
	$db = JFactory::getDbo();
	$id = (int) $id;

	$db->setQuery('select * from #__content where id = ' . $id);
	$row = $db->loadObject();
	if ($row) {
		$row->sellerEmail = 'a-lena@mail.ru';

		$attribs = json_decode($row->attribs);
		$row->amount = $attribs->attr_order_sum;
		$row->contact = $attribs->attr_order_contact;
		$row->email = $attribs->attr_order_email;
		$row->startdate = $attribs->attr_order_startdate;
		$row->enddate = $attribs->attr_order_enddate;
		$row->num = $attribs->attr_order_num;

		$db->setQuery('select * from #__content where id = ' . (int) $attribs->attr_order_items);
		$item = $db->loadObject();
		$row->title = $item->title;
	}

	return $row;
}

