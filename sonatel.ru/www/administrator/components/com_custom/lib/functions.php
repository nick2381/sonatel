<?php

function getCustomTypes() {
	static $types = null;

	if ($types == null) {
		$types = include dirname(__FILE__) . '/config-customtypes.php';
		$types['types'] = array();
		foreach ($types['categories'] as $categoryId => $type) {
			$types['types'][$type] = $categoryId;
		}
	}

	return $types;
}

function getCategoryByType($type) {
	$types = getCustomTypes();
	if (isset($types['types'][$type])) {
		return $types['types'][$type];
	}

	return 0;
}

function getCustomTypeByCategory($categoryId) {
	$types = getCustomTypes();
	if (!isset($types['categories'][$categoryId])) {
		return '';
	}

	return $types['categories'][$categoryId];
}

function getCustomFieldsByType($type) {
	$types = getCustomTypes();
	if (!isset($types['fields'][$type])) {
		return null;
	}

	return $types['fields'][$type];
}

function getCustomTypeById($categoryId) {
	$db = JFactory::getDbo();
	$type = getCustomTypeByCategory($categoryId);
	if ($type != '') {
		return $type;
	}

	// check parent id
	$query = 'select parent_id from #__categories
			where id = ' . (int) $categoryId;
	$db->setQuery($query);
	$row = $db->loadObject();

	if ($row && $row->parent_id) {
		return getCustomTypeById($row->parent_id);
	}

	return '';
}

function saveRowForCustomType($data) {
	$type = getCustomTypeById($data->catid);
	if ($type == '') {
		return true;
	}
	$fields = getCustomFieldsByType($type);
	if (empty($fields)) {
		return true;
	}

	$db = JFactory::getDbo();
	$tableName = '#__custom_fields_' . $type;
	$articleId = isset($data->id) ? $data->id : 0;
	if (empty($articleId)) {
		$update = false;
	}
	else {
		// check for separated data exists
		$db->setQuery('select count(*) from ' . $db->escape($tableName) . ' where id = ' . $articleId);
		$update = $db->loadResult() > 0;
	}

	$attribs = json_decode($data->attribs);
	if ($update) {
		$query = $db->getQuery(true)->update($tableName);
		$query->where('id = ' . $db->quote($articleId));
	}
	else {
		$query = $db->getQuery(true)->insert($tableName);
		$query->set('id = ' . $db->quote($articleId));
	}
	foreach ($fields as $field) {
		$attribName = 'attr_' . $type . '_' . $field;
		$query->set($db->escape($field) . ' = ' . $db->quote($attribs->{$attribName}));
	}

	$db->setQuery($query);
	if (!$db->query()) {
		throw new Exception($db->getErrorMsg());
	}

	return true;
}

function getExchangeRates() {
	// http://www.cbr.ru/scripts/Root.asp?Prtid=SXML
	// http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx
	$url = 'http://www.cbr.ru/scripts/XML_daily.asp';
	$fname = JPATH_ROOT . '/tmp/exchange_rates.xml';
	$lifetime = 24 * 60 * 60;

	$content = '';
	// fetch from cache
	if (file_exists($fname) && (filemtime($fname) > time() - $lifetime)) {
		$content = file_get_contents($fname);
	}
	// update from url
	if ($content == '') {
		$content = file_get_contents($url);
		file_put_contents($fname, $content);
	}
	if ($content == '') {
		return array();
	}

	try {
		$data = new SimpleXMLElement($content);
		$result = array();
		foreach ($data->xpath('//ValCurs/Valute') as $valute) {
			$type = (string) $valute->CharCode;
			if ($type == 'USD' || $type == 'EUR') {
				$result[$type] = (float) str_replace(',', '.', $valute->Value);
			}
		}
	}
	catch (Exception $e) {
		error_log($e);
	}

	return $result;
}

function setFilterStateForCategoryPage($model) {
	$id = JRequest::getInt('id');
	$app = JFactory::getApplication();

	switch ($id) {
		case getCategoryByType('partner'):
			$model->setState('filter.company_name',
					$app->getUserStateFromRequest('com_content.category.company_name', 'filter_company_name'));
			$model->setState('filter.company_city',
					$app->getUserStateFromRequest('com_content.category.company_city', 'filter_company_city'));
			$model->setState('filter.company_category',
					$app->getUserStateFromRequest('com_content.category.company_category',
							'filter_company_category'));
			break;
	}
}

function setFilterStateForArticles($src, $model) {
	$id = JRequest::getInt('id');

	switch ($id) {
		case getCategoryByType('partner'):
			/*
			  print '<pre>State for articles:<br>';
			  var_dump(
			  $src->getState('filter.company_name'), $src->getState('filter.company_city'),
			  $src->getState('filter.company_category')
			  );
			  print '</pre>';
			 */
			$model->setState('filter.company_name', $src->getState('filter.company_name'));
			$model->setState('filter.company_city', $src->getState('filter.company_city'));
			$model->setState('filter.company_category', $src->getState('filter.company_category'));
			break;
	}
}

function setFilterForArticlesQuery($model, $query, $db) {
	$id = JRequest::getInt('id');

	switch ($id) {
		case getCategoryByType('partner'):
			$name = $model->setState('filter.company_name');
			if ($name != '') {
				$query->where('a.title LIKE ' .
						$db->Quote('%' . $db->escape($name, true) . '%', false));
			}

			$city = $model->setState('filter.company_city');
			if ($city != '') {
				$query->where('cf_p.city LIKE ' .
						$db->Quote('%' . $db->escape($city, true) . '%', false));
			}

			$category = $model->setState('filter.company_category');
			if ($category != '') {
				$query->where('cf_p.category LIKE ' .
						$db->Quote('%' . $db->escape($category, true) . '%', false));
			}

			if ($city != '' || $category != '') {
				$query->join('LEFT', '#__custom_fields_partner AS cf_p ON a.id = cf_p.id');
			}

			//var_dump((string) $query);

			break;
	}
}

// patches for core

function custompatch_logErrorLogin($response, $credentials) {
	if ($response->error_message != '') {
		error_log('Failed authorization ' . $_SERVER['REQUEST_URI'] . ', ' .
				$_SERVER['HTTP_USER_AGENT'] . ', ' . $_SERVER['REMOTE_ADDR'] . ', ' .
				$credentials['username'] . ' - ' . $credentials['password']);
	}
}
