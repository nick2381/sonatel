<?php
defined('_JEXEC') or die;

$tmp = explode('?', $_SERVER['REQUEST_URI'], 2);
$currentPath = $tmp[0];

foreach ($list as $item) :
	// FIX link for catalog
	$url = JRoute::_(preg_replace('{Itemid=\d+}si', 'Itemid=117', 
			ContentHelperRoute::getCategoryRoute($item->id)));

?>
	<li <?php if ($currentPath == $url) echo ' class="active"';?>> <?php $levelup=$item->level-$startLevel -1; ?>
  	<a href="<?php echo $url; ?>">
			
		<?php echo $item->title;?></a>   

		<?php
		if($params->get('show_description', 0))
		{
			echo JHtml::_('content.prepare', $item->description, $item->getParams(), 'mod_articles_categories.content');
		}
		if($params->get('show_children', 0) && (($params->get('maxlevel', 0) == 0) || ($params->get('maxlevel') >= ($item->level - $startLevel))) && count($item->getChildren()))
		{

			echo '<ul>';
			$temp = $list;
			$list = $item->getChildren();
			require JModuleHelper::getLayoutPath('mod_articles_categories', $params->get('layout', 'default').'_items');
			$list = $temp;
			echo '</ul>';
		}
		?>
 </li>
<?php endforeach; ?>
