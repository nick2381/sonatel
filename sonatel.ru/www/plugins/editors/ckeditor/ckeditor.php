<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgEditorCKeditor extends JPlugin {

	public $pluginsName = array();
	public $called = false;
	private $mediaUrl;

	function plgEditorCKeditor(&$subject, $config) {
		parent::__construct($subject, $config);

		$this->mediaUrl = JURI::root(true) . '/media';
	}

	function onInit() {
		$doc = JFactory::getDocument();

		$base = $this->mediaUrl . '/editors/ckeditor/';
		$doc->addScript($base . 'ckeditor.js');

		$doc->addScriptDeclaration('
			window.CKEDITOR_BASEPATH = "' . $base . '";
		');
	}

	function onGetContent($editor) {
		return " CKEDITOR.instances.$editor.getData(); ";
	}

	function onSetContent($editor, $html) {
		return " CKEDITOR.instances.$editor.setData($html); ";
	}

	function onDisplay(
	$name, $content, $width, $height, $col, $row, $buttons = true, $id = null, $asset = null,
			$author = null) {

		$app = JFactory::getApplication();

		$session = JFactory::getSession();
		$user = JFactory::getUser();
		JHTML::_('behavior.modal', 'a.modal-button');
		if ((int) $width) {
			$width .= 'px';
		}
		if ((int) $height) {
			$height .= 'px';
		}
		$editor = '<textarea name="' . $name . '" id="' . $id . '"
			class="ckeditor"
			cols="' . $col . '" rows="' . $row . '" style="width:' . $width . '; height:' . $height . '">' . $content . '</textarea>';
		$frontend = '';
		if (!strpos(JPATH_BASE, 'administrator')) {
			$frontend = '_frontEnd';
		}

		$language = '';
		if ($this->params->get('CKEditorAutoLang', 0) == 0) {
			$language = "language : '" . $this->params->get('language', 'en') . "',";
		}
		else {
			$language .= "defaultLanguage : '" . $this->params->get('language', 'en') . "',";
		}

		if ($this->params->get('CKEditorLangDir', 0) == 1) {
			$txtDirection = "contentsLangDirection : 'rtl',";
		}
		else {
			$txtDirection = "contentsLangDirection : 'ltr',";
		}

		$editor .= $this->_displayButtons($id, $buttons, $asset, $author);

		return $editor;
	}

	function onGetInsertMethod($name) {
		$document = JFactory::getDocument();
		$url = str_replace('administrator/', '', JURI::base());
		$js = "function jInsertEditorText( text,editor ) {
			text = text.replace( /<img src=\"/, '<img src=\"" . $url . "' ); CKEDITOR.instances[editor].insertHtml( text);}";
		if (!$this->called) {
			$document->addScriptDeclaration($js);
			$this->called = true;
		}
		return true;
	}

	protected function _displayButtons($name, $buttons, $asset, $author) {
		// Load modal popup behavior
		JHTML::_('behavior.modal', 'a.modal-button');

		$args['name'] = $name;
		$args['event'] = 'onGetInsertMethod';

		$return = '';
		$results[] = $this->update($args);

		foreach ($results as $result) {
			if (is_string($result) && trim($result)) {
				$return .= $result;
			}
		}

		if (is_array($buttons) || (is_bool($buttons) && $buttons)) {
			$results = $this->_subject->getButtons($name, $buttons, $asset, $author);
			/*
			 * This will allow plugins to attach buttons or change the behavior on the fly using AJAX
			 */
			$return .= "\n<div id=\"editor-xtd-buttons-" . $name . "\">\n";

			foreach ($results as $button) {
				/*
				 * Results should be an object
				 */
				if ($button->get('name')) {
					$modal = ($button->get('modal')) ? 'class="modal-button"' : null;
					$href = ($button->get('link')) ? 'href="' . JURI::base() . $button->get('link') . '"' : null;
					$onclick = ($button->get('onclick')) ? 'onclick="' . $button->get('onclick') . '"' : 'onclick="return false;"';
					$return .= "<div class=\"button2-left\"><div class=\"" . $button->get('name') . "\"><a " . $modal . " title=\"" . $button->get('text') . "\" " . $href . " " . $onclick . " rel=\"" . $button->get('options') . "\">" . $button->get('text') . "</a></div></div>\n";
				}
			}

			$return .= "</div>\n";
		}

		return $return;
	}

	protected function _toogleButton($name) {
		$return = '';
		$return .= "\n<div class=\"toggle-editor\">\n";
		$return .= "<div class=\"button2-left\"><div class=\"blank\"><a href=\"#\" onclick=\"javascript:tinyMCE.execCommand('mceToggleEditor', false, '$name');return false;\" title=\"" . JText::_('PLG_TINY_BUTTON_TOGGLE_EDITOR') . "\">" . JText::_('PLG_TINY_BUTTON_TOGGLE_EDITOR') . "</a></div></div>";
		$return .= "</div>\n";

		return $return;
	}

}
