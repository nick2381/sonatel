<?php

// Should we show the new or old layouts?
$jversion = new JVersion();
$new_layout = (version_compare($jversion->getShortVersion(), '2.0.0') >= 0) ? true : false;

if ($layout == 'edit' or $layout == 'add') {

	if (!class_exists('phpQuery')) {
	    require_once($this->templatePath . "/lib/phpQuery.php");
	}


	$js_init = "window.addEvent('domready', function(){
	 	toggler = document.id('mc-article-tabs')
	  	element = document.id('mc-article')
	  	if(element) {
	  		document.switcher = new JSwitcher(toggler, element);
	  	}
	});";

	// add the tabber switcher js
	$this->document->addScript($this->templateUrl.'/js/MC.Switcher.js');
	$this->addInlineScript($js_init);

	$pq = phpQuery::newDocument($buffer);

	if ($new_layout) {
		$framework = '<div id="mc-article-key" class="adminform" />
					  <ul id="mc-article-tabs" class="mc-form-tabs">
					  	<li><a id="editor" class="active">'.JText::_('MC_TAB_ARTICLE_EDITOR').'</a></li>
						<li><a id="publishing">'.JText::_('MC_TAB_PUBLISHING_METADATA').'</a></li>
						<li><a id="advanced">'.JText::_('MC_TAB_ADVANCED_PERMS').'</a></li>
					  </ul>
					  <div id="mc-article" class="adminform">
					   	<div id="page-editor" />
					   	<div id="page-publishing">
					   		<div id="mc-pubdata">
					   			<div class="mc-block">
					   				<h3>'.JText::_('MC_TAB_PUBLISHING_OPTIONS').'</h3>
					   			</div>
								<div class="mc-block">
					   				<h3>'.JText::_('MC_TAB_CONFIGURE_EDIT').'</h3>
					   			</div>
					   		</div>
					   		<div id="mc-metadata">
					   			<div class="mc-block">
					   				<h3>'.JText::_('MC_TAB_METADATA').'</h3>
					   			</div>
								<div class="mc-block">
					   				<h3>'.JText::_('MC_TAB_IMAGES_LINKS').'</h3>
					   			</div>
					   		</div>
					   	</div>
					   	<div id="page-advanced">
					   		<div id="mc-settings">
					   			<div class="mc-block">
					   				<h3>'.JText::_('MC_TAB_ADVANCED').'</h3>
					   			</div>
					   		</div>
					   		<div id="mc-permissions">
					   			<div class="mc-block">
					   				<h3>'.JText::_('MC_TAB_ARTICLE_PERMS').'</h3>
					   			</div>
					   		</div>
					   	</div>
					  </div>';
	} else {
		$framework = '<div id="mc-article-key" class="adminform" />
					  <ul id="mc-article-tabs" class="mc-form-tabs">
					  	<li><a id="editor" class="active">'.JText::_('MC_TAB_ARTICLE_EDITOR').'</a></li>
						<li><a id="publishing">'.JText::_('MC_TAB_PUBLISHING_METADATA').'</a></li>
						<li><a id="advanced">'.JText::_('MC_TAB_ADVANCED_PERMS').'</a></li>
					  </ul>
					  <div id="mc-article" class="adminform">
					   	<div id="page-editor" />
					   	<div id="page-publishing">
					   		<div id="mc-pubdata">
					   			<div class="mc-block">
					   				<h3>'.JText::_('MC_TAB_PUBLISHING_OPTIONS').'</h3>
					   			</div>
					   		</div>
					   		<div id="mc-metadata">
					   			<div class="mc-block">
					   				<h3>'.JText::_('MC_TAB_METADATA').'</h3>
					   			</div>
					   		</div>
					   	</div>
					   	<div id="page-advanced">
					   		<div id="mc-settings">
					   			<div class="mc-block">
					   				<h3>'.JText::_('MC_TAB_ADVANCED').'</h3>
					   			</div>
					   		</div>
					   		<div id="mc-permissions">
					   			<div class="mc-block">
					   				<h3>'.JText::_('MC_TAB_ARTICLE_PERMS').'</h3>
					   			</div>
					   		</div>
					   	</div>
					  </div>';

	}

	pq('form[name=adminForm')->prepend($framework);
	pq('#mc-article-key')->append(pq('form[name=adminForm] fieldset.adminform > ul.adminformlist'));
	pq('#page-editor')->append(pq('form[name=adminForm] .width-60.fltlft > fieldset.adminform'));

	if ($new_layout) {
		pq('#mc-pubdata .mc-block:eq(0)')->append(pq('.width-40 .panel:eq(0) fieldset.panelform'));
		pq('#mc-pubdata .mc-block:eq(1)')->append(pq('.width-40 .panel:eq(2) fieldset.panelform'));
		pq('#mc-settings .mc-block')->append(pq('.width-40 .panel:eq(1) fieldset.panelform'));
		pq('#mc-metadata .mc-block:eq(0)')->append(pq('.width-40 .panel:eq(4) fieldset.panelform'));
		pq('#mc-metadata .mc-block:eq(1)')->append(pq('.width-40 .panel:eq(3) fieldset.panelform'));
	} else {
		pq('#mc-pubdata .mc-block')->append(pq('.width-40 .panel:eq(0) fieldset.panelform'));
		pq('#mc-settings .mc-block')->append(pq('.width-40 .panel:eq(1) fieldset.panelform'));
		pq('#mc-metadata .mc-block')->append(pq('.width-40 .panel:eq(2) fieldset.panelform'));
	}

	pq('#mc-permissions .mc-block')->append(pq('.width-100 .pane-sliders'));


	if ($layout == 'edit') {

		// if roktracking found, add editor block
		if (file_exists($this->basePath.DS.'plugins'.DS.'system'.DS.'roktracking'.DS.'roktracking.php')) {

			$limit = 10;

			$cid = JRequest::getVar('id');
			if (is_array($cid)) $cid = $cid[0];

			$db = JFactory::getDBO();
			$query = 'select r.*, u.name, u.username, u.email,e.name as extension from #__rokadminaudit as r, #__users as u, #__extensions as e where r.user_id = u.id and e.element = r.option and r.cid = '.$cid.' and (r.task ="apply" or r.task="save") order by id desc limit '. intval($limit);
			$db->setQuery($query);

			$results = $db->loadObjectList();

			$editors = '<h3 class="title">'.JText::_('MC_TAB_EDITORS').'</h3>';
            if (!empty($results)) {
                $editors .= '<div class="mc-editors-list"><ul>';

                foreach ($results as $r) {
                    $editors .= '<li>'.$r->username.' ('.$r->timestamp.')</li>';
                }
                $editors .= '</ul></div>';
            } else {
                $editors .= JText::_('MC_TAB_NO_EDITORS');
            }

            if ($new_layout) {
				pq('#mc-pubdata .mc-block:eq(1)')->append($editors);
			} else {
				pq('#mc-pubdata .mc-block:eq(0)')->append($editors);
			}
		}
	}

	// tweaks
	pq('#mc-article-key ul.adminformlist > li:first-child > input')->addClass('mc-bigger-field');

	// remove unused bits
	pq('#mc-article-key span.faux-label')->parent('li')->remove();
	pq('#page-editor legend')->remove();
	pq('#jform_articletext-lbl')->remove();
	pq('#mc-permissions .pane-sliders:eq(0)')->remove();
	pq('.rule-desc')->remove();
	pq('.width-60.fltlft')->remove();
	pq('.width-40.fltrt')->remove();
	pq('.width-100.fltft')->remove();

	$buffer = $pq->getDocument()->htmlOuter();
	$this->document->setBuffer($buffer, 'component');
}





