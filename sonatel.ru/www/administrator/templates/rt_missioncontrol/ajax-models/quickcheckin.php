<?php
/**
 * @version � 2.6 April 10, 2012
 * @author � �RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license � http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );
jimport('joomla.cache.cache');

global $mctrl;
require_once(dirname(__FILE__).DS.'..'.DS.'lib'.DS.'rtcheckin.class.php');

$action = JRequest::getString('action',null);
if ($action == 'checkin') {
	RTCheckin::checkin(array_keys(RTCheckin::getCheckouts()));
	$result = 0;
} else {
	$result = RTCheckin::getCheckouts(true);
}

//return count
echo ($result);


?>

