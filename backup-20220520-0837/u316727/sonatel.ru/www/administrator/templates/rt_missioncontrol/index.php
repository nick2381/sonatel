<?php
/**
 * @version 2.6 April 10, 2012
 * @author  RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );

// load and init the MissioControl Class
require_once('lib/missioncontrol.class.php');
global $option;
global $mctrl;

$mctrl = MissionControl::getInstance();
$mctrl->initRenderer();
$mctrl->addStyle("core.css",CURRENT_VERSION);
$mctrl->addStyle("menu.css",CURRENT_VERSION);
$mctrl->addStyle("colors.css.php");
$mctrl->addScript('MC.js',CURRENT_VERSION);
$mctrl->addOverrideStyles();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $mctrl->language; ?>" lang="<?php echo $mctrl->language; ?>" dir="<?php echo $mctrl->direction; ?>">
	<head>
		<jdoc:include type="head" />
	</head>
	<body id="mc-standard" class="<?php $mctrl->displayBodyTags(); ?>">
		<div id="mc-frame">
			<div id="mc-header">
				<div class="mc-wrapper">
					<div id="mc-status">
						<?php $mctrl->displayStatus(); ?>
					</div>
					<div id="mc-logo">
						<?php $mctrl->displayLogo(); ?>
						<?php $mctrl->displayTitle(); ?>
						<?php $mctrl->displayHelpButton(); ?>
					</div>
					<div id="mc-nav">
						<?php $mctrl->displayMenu(); ?>
					</div>
					<div id="mc-userinfo">
						<div class="mc-userinfo2">
						<?php $mctrl->displayUserInfo(); ?>
						</div>
					</div>
					<div class="mc-clr"></div>
				</div>
			</div>
			<div id="mc-body">
				<div class="mc-wrapper">
					<jdoc:include type="message" />
					<div id="content-box">
						<div id="toolbar-box"><div class="m">
							<div id="mc-title">								
								<?php $mctrl->displayToolbar(); ?>
								<div class="mc-clr"></div>
							</div>
							<?php if (!JRequest::getInt('hidemainmenu')): ?>
							<div id="mc-submenu">
								<?php $mctrl->displaySubMenu(); ?>
							</div>
						<?php endif; ?>
						</div></div>
					</div>

					<?php if ($option == 'com_cpanel') : ?>
					<div id="mc-sidebar">
						<jdoc:include type="modules" name="sidebar" style="sidebar"  />
					</div>
					<div id="mc-cpanel">
						<?php $mctrl->displayDashText(); ?>
						<jdoc:include type="modules" name="dashboard" style="standard"  />
					<?php endif; ?>

					<div id="mc-component" class="clearfix">
						<jdoc:include type="component" />
					</div>
					<?php if ($option == 'com_cpanel') : ?>
					</div>
					<?php endif; ?>
					<div class="mc-clr"></div>
				</div>
			</div>
			<div id="mc-footer">
				<div class="mc-wrapper">
					<p class="copyright">
						<span class="mc-footer-logo"></span>
						 <?php $joomla= '<a href="http://www.joomla.org">Joomla!&#174;</a>';
	 echo JText::sprintf('JGLOBAL_ISFREESOFTWARE', $joomla) ?> - <?php echo  JText::_('Version') ?> <?php echo  JVERSION; ?><br />
						<?php echo JText::_('MC_FOOTER') ?> (MC Version <?php echo CURRENT_VERSION; ?>)
					</p>
				</div>
			</div>
			<div id="mc-message">

			</div>
		</div>
	</body>
</html>
