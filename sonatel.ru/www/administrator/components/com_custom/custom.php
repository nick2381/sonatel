<?php

defined('_JEXEC') or die('Restricted access');

$option = JRequest::getWord('option');
$controller = JRequest::getWord('controller', 'api');
$task = JRequest::getCmd('task');

require 'controllers/' . $controller . '.php';

$user = JFactory::getUser();
$className = 'CustomController' . ucfirst($controller);
$controllerObj = new $className();
$controllerObj->execute($task);
$controllerObj->redirect();
