<?php

JHtml::_('behavior.framework', true);

$document = JFactory::getDocument();
$document->setGenerator('GNU Emacs');

$app = JFactory::getApplication();
$templatePath = $this->baseurl . '/templates/' . $this->template;
$mediaPath = JURI::root(true) . '/media';
$lang = JRequest::getVar('lang', 'ru');

$isMainPage = $this->countModules('mainslider') > 0;

$catid = JRequest::getVar('catid', null);
$id = JRequest::getVar('id', null);

$isCatalog = $catid == 14;

// current menu item
$menu = $app->getMenu()->getActive();
$pageclass = is_object($menu)? $menu->params->get('pageclass_sfx') : '';

//$user = JFactory::getUser();
//$userGroups = $user->getAuthorisedGroups();
//$isAdmin = $user->get('isRoot');
//if($user->authorise('core.edit', 'com_contact')) { ... }
