<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgContentCustomFields extends JPlugin {

	function __construct(& $subject, $config) {
		parent::__construct($subject, $config);

		//...
	}

	function onContentAfterSave($context, $data, $isNew) {
		$app = JFactory::getApplication();
		if ($app->isSite()) {
			return true;
		}
		if (!in_array($context, array('com_content.article'))) {
			return true;
		}

		$result = saveRowForCustomType($data);

		return $result;
	}

	function onContentAfterDelete($context, $data) {
		$articleId = isset($data->id) ? $data->id : 0;
		if (empty($articleId)) {
			return true;
		}
		$type = getCustomTypeById($data->catid);
		if ($type == '') {
			return true;
		}
		$fields = getCustomFieldsByType($type);
		if (empty($fields)) {
			return true;
		}

		$tableName = '#__custom_fields_' . $type;
		try {
			$db = JFactory::getDbo();
			$db->setQuery('delete from ' . $db->escape($tableName) . ' where id = ' . $articleId);
			if (!$db->query()) {
				throw new Exception($db->getErrorMsg());
			}
		}
		catch (JException $e) {
			$this->_subject->setError($e->getMessage());
			return false;
		}

		return true;
	}

}
