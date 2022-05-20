<?php
/**
 * @version 2.6 April 10, 2012
 * @author RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );

require_once('rtcore.class.php');
define("UPDATE_SLUG", 'missioncontrol-admin-template');
define("UPDATE_NAME", 'MissionControl Admin Template');
define("UPDATE_URL", 'http://updates.rockettheme.com/joomla/templates/missioncontrol-updates.json');
define("CURRENT_VERSION", '2.6');

class MissionControl extends RTCore {
    private static $instance;

	var $core;


    public static function getInstance(){
        if (!self::$instance)
        {
            self::$instance = new MissionControl();
        }
        return self::$instance;
    }

	function __construct() {
		global $option;

		parent::__construct();
        $this->updateSlug = UPDATE_SLUG;
        $this->updateUrl = UPDATE_URL;
        $this->params->set('update_name',UPDATE_NAME);
        $this->params->set('update_slug',UPDATE_SLUG);
        $this->params->set('current_version',CURRENT_VERSION);

        if ($option=='') $option = 'com_cpanel';


	}

    function addCustomHeaders() {

        $inline_css = '';
        if ($inline_css) $this->addInlineStyle($inline_css);
    }

	function displayLoginForm() {
		require_once($this->templatePath.DS.'html'.DS.'mod_login'.DS.'default.php');
	}

	function displayMenu() {

        //$custom_menu = $this->templatePath.DS.'html'.DS.'mod_menu'.DS.'rtmenuhelper.class.php';

   	    require_once('menu'.DS.'menu.php' );

	}

	function displayBodyTags() {

		$option = JRequest::getCmd('option');
		$task = JRequest::getString('task');
		$view = JRequest::getString('view');

		$params = $this->params;

		echo "transitions-". $params->get('enableTransitions') ." ";
        echo "headers-".$params->get('enableFancyHeaders') . " ";
        echo "extendmenu-".$params->get('extendmenu'). " ";
        echo "menuwidth-".$params->get('menuwidth'). " ";
        echo "width-".$params->get('templateWidth') . " ";
        echo "avatar-".$params->get('enableGravatar') . " ";
		echo $this->document->direction ." ";

		echo ("option-".str_replace("_","-",$option)." task-".str_replace("_","-",$task)." view-".str_replace("_","-",$view));
	}


	function displaySubMenu() {
		global $option;
	    echo '<jdoc:include type="modules" name="submenu" style="rounded" id="submenu-box" />';
	}

	function displayDashText() {

		$params = $this->params;

		$default = 'You can put anything you want here to provide some information to your Administrators. <a href="index.php?option=com_templates&task=edit&cid[]=rt_missioncontrol&client=1">Edit This Text Now...</a>';
		$dashtext = $params->get('dashboard');

		echo '<p class="mc-dashtext">'.$dashtext.'</p>';
	}

    function displayLogo() {

        $dest = 'images/missioncontrol-logo.png';

        if (file_exists($dest)) {
            $logo_url = $dest;
            $size = getimagesize($dest);
        } else {
            $logo_url = $this->templateUrl."/images/logo.png";
            $size = getimagesize($this->templatePath.DS.'images'.DS.'logo.png');
        }
        $logo_url .= '?'.intval(microtime(true));

        echo '<img src="'.$logo_url.'" alt="logo" class="mc-logo" '.$size[3] .' />';
    }




	function displayLoginStatus() {

		$browserLang = null;
		$output = array();
		$cancel = array();

		$output[] = $this->_addListItem('<a href="'.$this->baseUrl.'">'.JText::_('MC_FRONTEND').'</a>','action');

		if ($this->params->get('showlangmenu',1)) {

			$languages = array();
			$languages = JLanguageHelper::createLanguageList($browserLang );
			array_unshift( $languages, JHTML::_('select.option',  '', JText::_( 'MC_LANGUAGE_DEFAULT' ) ) );
			$langs = JHTML::_('select.genericlist',   $languages, 'lang', ' class="inputbox"', 'value', 'text', $browserLang );

			$output[] = $this->_addListItem($langs,'mdropdown');
		}

		echo $this->_listify($output);

	}

	function displayUserInfo() {

		$db			= JFactory::getDBO();
        $user       = JFactory::getUser();
		$task 		= JRequest::getString('task');

		$disabled = ($task == 'edit' || $task == 'editA' || JRequest::getInt('hidemainmenu'));
		$disabled_class = $disabled ? ' disabled' : ' active';
        $output = '';

		$lastvisit = JHTML::_('date', $this->user->lastvisitDate, 'Y-m-d H:i:s');

		// Get the number of unread messages in your inbox
		$query = 'SELECT COUNT(*)'
		. ' FROM #__messages'
		. ' WHERE state = 0'
		. ' AND user_id_to = '.(int) $user->get('id');
		$db->setQuery( $query );
		$unread = $db->loadResult();

		if ($disabled)
			$inboxLink = '<a>';
		else
			$inboxLink = '<a href="index.php?option=com_messages">';

		// Print the inbox message
		if (intval($unread) > 0)
			$messages = $inboxLink.'(<span class="unread-messages">'.$unread.'</span>) '.JText::_('MC_MESSAGES').'</a>';
		else
			$messages = $inboxLink.'(<span class="no-unread-messages">'.$unread.'</span>) '.JText::_('MC_MESSAGES').'</a>';

		if (!$disabled)
			$edit_link = '<a href="index.php?option=com_admin&task=profile.edit&id='.$user->id.'">';
		else
			$edit_link = '<a>';
		if ($this->params->get('enableGravatar')) {
            $gravatar = $this->_getGravatar($this->user->email,46);
		    $output .= '<div class="gravatar"><img src="'.$gravatar.'" alt="gravatar" /></div>';
        }
		$output .= '<div class="userinfo'.$disabled_class.'">';
		$output .= '<b>'.$this->user->name . '</b>';
		$output .= '<span class="mc-button">'.$edit_link.JTEXT::_('MC_EDIT_BUTTON').'</a></span>';
        $output .= '<span class="mc-messages">'.$messages . '</span>';
		$output .= JTEXT::_('MC_LAST_VISIT').': ' . $lastvisit;
		$output .= '</div>';

		// session expiration countdown
		if ($this->params->get('enableSessionBar',1)) {
			$output .= '<div class="session_expire">';
			$output .= '<div class="session_progress"></div>';
			$output .= '</div>';
		}



		echo $output;

	}

	function displayTitle() {

		$option = JRequest::getCmd('option');
		$mainframe = JFactory::getApplication();

		if ($option == "com_cpanel")
			$title = JText::_('MC_SITE_DASHBOARD');
		else
			$title = $mainframe->get('JComponentTitle');

		if (!empty($title)) {
			echo '<h1>'.strip_tags($title).'</h1>';
		} else {
			$document = JFactory::getDocument();
			$buffer = $document->getBuffer();
			if(isset($buffer['modules']['title'])) echo '<h1>'.strip_tags($buffer['modules']['title']).'</h1>';
		}
	}

	function displayHelpButton() {
		$option = JRequest::getCmd('option');
		if ($this->params->get("showhelpbutton",1) && $option != "com_cpanel") {
			echo $this->help->render('help');
		}
	}

	function displayToolbar() {

		echo $this->toolbar_output;

	}

	function displayStatus() {

		$task 		= JRequest::getString('task');

		$user		= $this->user;
		$db			= JFactory::getDBO();
		$output 	= array();

		$canConfig	= $user->authorize('com_config', 'manage');


		$disabled = ($task == 'edit' || $task == 'editA' || JRequest::getInt('hidemainmenu'));
		$disabled_class = $disabled ? 'disabled' : 'active';

		// add logout button
		if ($disabled ) {
			 $output[] = $this->_addListItem("<span class=\"logout\"><a>".JText::_('MC_LOGOUT')."</a></span>","inactive");
        } else {
            $logoutLink = JRoute::_('index.php?option=com_login&task=logout&'. JUtility::getToken() .'=1');
			$output[] = $this->_addListItem("<span class=\"logout\"><a href=\"".$logoutLink."\">".JText::_('MC_LOGOUT')."</a></span>","action");
        }
		// Print the preview button
		if ($this->params->get('enableViewSite')) {
			$output[] = '<span class="preview"><a href="'.JURI::root().'" target="_blank">'.JText::_('MC_VIEW_SITE').'</a></span>';
		}

		//render all modules except mod_status
		$renderer =  $this->document->loadRenderer('module');
		foreach (JModuleHelper::getModules('status') as $mod)  {
			if ($mod->module != 'mod_status') $output[] = $renderer->render($mod, null, null);
		}


		// display Tools
		$tools = $this->_getTools();
		if (!$disabled && $tools)
			$output[] = array('<a href="#" id="ToolsToggle"><span class="select-active">'.JTEXT::_('MC_SYSTEM_TOOLS').'</span><span class="select-arrow">&#x25BE;</span></a>'.$tools, 'mdropdown');
		else
			$output[] = '<span><a>'.JTEXT::_('MC_SYSTEM_TOOLS').'</a></span>';

        // display editors
        if ($this->params->get('enableQuickEditor')) {
        	$output[] = array($this->_renderEditorSelect(), 'mdropdown quickedit');
        }

        // session expire
        $document = JFactory::getDocument();
		$config = JFactory::getConfig();
		$lifetime = ($config->get('lifetime') * 60000);

		$document->addScriptDeclaration('
			var MCSessionTimeout = ' . $lifetime . ';
			var MCSessionLang = {
				"expired": "'.JTEXT::_('MC_EXPIRED').'"
			}
		');


		echo $this->_listify($output,$disabled_class);
	}

    function _getEditors() {

        $dbo = JFactory::getDBO();
		$query = 'SELECT element, name '.
			'FROM #__extensions '.
			'WHERE type = "plugin" '.
			'AND folder = "editors" '.
			'AND enabled = 1 '.
			'ORDER BY ordering, name';
		$dbo->setQuery($query);
		$editors = $dbo->loadObjectList();

		return $editors;

    }

    function _renderEditorSelect() {

        $conf = JFactory::getConfig();
        $myEditor = $conf->getValue('config.editor');
        $user = JFactory::getUser();

        $editor_script = "\n
			var updateEditor = function(){
				var editor = document.id('editor_selection');
				var xhr = new Request({
					url: 'index.php?process=ajax&model=quickeditor&id=".$user->get('id')."&editor=' + editor.value + '&nocache=' + (Date.now() + Math.random(0, 50000)).toInt(),
					method: 'get',
					onRequest: editorAjax.request,
					onSuccess: editorAjax.success,
				}).send();
			};

			var editorAjax = {
				'request': function(){
					document.id('editor_spinner').setStyle('display', 'block');
					document.id('editor_selection').getParent().getFirst().setStyle('margin-left', 10);
				},
				'success': function(){
					document.id('editor_spinner').setStyle('display', 'none');
					document.id('editor_selection').getParent().getFirst().setStyle('margin-left', 0);
				}
			};

			window.addEvent('domready', function(){
				document.id('editor_selection').addEvent('change', updateEditor);
			});\n";

        $this->addInlineScript($editor_script);

        $output = '<select id="editor_selection">';
        foreach ($this->_getEditors() as $editor) {
            if ($myEditor == $editor->element)
                $output .= '<option value="'.$editor->element.'" selected="selected">'.JTEXT::_('MC_EDITOR').' - '.ucfirst($editor->element).'</option>';
	        else
		        $output .= '<option value="'.$editor->element.'">'.JTEXT::_('MC_EDITOR').' - '.ucfirst($editor->element).'</option>';

        }
        $output .= '</select><div id="editor_spinner" class="spinner"></div>';

        return $output;
    }

}
global $option;
$option = JRequest::getCmd('option');
