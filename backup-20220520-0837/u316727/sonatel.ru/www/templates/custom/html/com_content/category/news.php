<?php
defined('_JEXEC') or die;
?>

<h1><?= $this->escape($this->params->get('page_heading')) ?></h1>

<div class="content-list">

<?php
foreach ($this->items as &$item) {
	$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
	//$date = JHTML::date($item->created, 'j') . ' ' .
	//		JText::_('MSG_MONTH' . JHTML::date($item->created, 'm')) . ' ' .
	//		JHTML::date($item->created, 'Y');
	$date = JHTML::date($item->created, 'j.m.Y');
	$images = json_decode($item->images);
	$thumb = $images->image_intro;
	?>

	<div class="item">
		<?php if ($thumb) { ?>
		<img src="<?= $thumb ?>" alt="<?= $item->title ?>" class="small" />
		<?php } ?>		
		<div class="description">
			<h3><a href="<?= $link ?>"><?= $item->title ?></a> <span class="date"><?= $date ?></span></h3>
			<?= $item->introtext ?>
		</div>
	</div>

<?php } ?>

	<?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) { ?>
	<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)) { ?>
			<p class="counter">
			<?= $this->pagination->getPagesCounter() ?>
			</p>
		<?php } ?>
	<?= $this->pagination->getPagesLinks() ?>
	</div>
<?php } ?>

</div>
