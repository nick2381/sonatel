<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemCustom extends JPlugin {

	private $mediaUrl;
	private $assetsUrl;

	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);

		$this->mediaUrl = JURI::root(true) . '/media';
		$this->assetsUrl = $this->mediaUrl . '/com_custom/assets';
	}

	function initCustomTypes($document, $app) {
		if ($app->isSite()) {
			return;
		}

		$document->addScript($this->assetsUrl . '/js/customtypes.js?v=0.2');
		$document->addScript($this->assetsUrl . '/js/customtype-product.js?v=0.1');
		$document->addScript($this->assetsUrl . '/js/customtype-order.js?v=0.1');
		if (JRequest::getVar('view') == 'article') {
			$exchangeRates = getExchangeRates();
			$document->addScriptDeclaration('
				SiteApp.ExchangeRates = ' . json_encode($exchangeRates) . ';
			');
		}
	}

	function onBeforeCompileHead() {
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$lang = JRequest::getVar('lang', 'ru');
		$baseUrl = JURI::root(true);

		$assetsList = require JPATH_ROOT . '/media/com_custom/config-assets.php';
		if (isset($assetsList['style'])) {
			foreach ($assetsList['style'] as $fname) {
				$document->addStyleSheet($baseUrl . $fname);
			}
		}
		if (isset($assetsList['script'])) {
			foreach ($assetsList['script'] as $fname) {
				$document->addScript($baseUrl . $fname);
			}
		}
		$document->addScriptDeclaration('
			var SiteApp = (function(app) {
				app.language = "' . $lang . '";
				return app;
			})(SiteApp || {});
		');

		if ($app->isSite()) {
			$fname = JPATH_ROOT . '/templates/' . $document->template . '/config-assets.php';
			if (file_exists($fname)) {
				$assetsList = require $fname;
				if (isset($assetsList['style'])) {
					foreach ($assetsList['style'] as $fname) {
						$document->addStyleSheet($baseUrl . $fname);
					}
				}
				if (isset($assetsList['script'])) {
					foreach ($assetsList['script'] as $fname) {
						$document->addScript($baseUrl . $fname);
					}
				}
			}
		}

		$this->initCustomTypes($document, $app);

		//...
	}

}
