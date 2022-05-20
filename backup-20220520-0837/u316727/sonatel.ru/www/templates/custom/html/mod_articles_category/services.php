<?php
defined('_JEXEC') or die;

?>

<div class="services">
<div class="services-header">Перспективные направления</div>

<ul>
	<?php foreach ($list as $item) {
		$url = ContentHelperRoute::getArticleRoute($item->slug, $item->catid);
		$url = preg_replace('{Itemid=\d+}si', 'Itemid=143', $url);
		$link = JRoute::_($url);
		$tmp = explode(' ', $item->title);
	?>
	<li><img alt="<?= $item->title ?>" src="/images/comp-icon.png" /> <a 
			href="<?= $link ?>"><?= count($tmp) == 2? implode('<br>', $tmp) : $item->title ?></a></li>
	<?php } ?>	
</ul>

<div class="clear"></div>
</div>

<?php /*foreach ($list as $group_name => $group) { ?>

	<article id="blocks">
	<?php
	foreach ($group as $item) {
		$images = json_decode($item->images);
		$thumb = $images->image_intro;

		$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
		?>
		<dl>
		<dt><a href="<?= $link ?>"><img alt="<?= $item->title ?>" src="<?= $thumb ?>" /></a></dt>
		<dd><?= $item->introtext ?></dd>
		</dl>
	<?php } ?>
	</article>

<?php }*/ ?>