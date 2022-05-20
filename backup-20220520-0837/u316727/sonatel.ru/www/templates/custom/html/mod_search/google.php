<?php
defined('_JEXEC') or die;

$searchWord = JRequest::getVar('searchword', '', 'post');
?>

<form id="search" action="/search.html" method="post">
  <input type="text"
				 placeholder="<?= JText::_('MOD_SEARCH_LABEL_TEXT1') ?>"
				 size="20"
				 class="inputbox"
				 maxlength="100"
				 value="<?= $searchWord ?>"
				 id="mod_search_searchword"
				 name="searchword">
	<a href="#" id="search-btn"></a>

</form>

<script>
  (function() {
    var cx = '017148934014551611796:jsqzbq_ig20';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>

<div style="display:none;">
	<gcse:searchbox></gcse:searchbox>
</div>
