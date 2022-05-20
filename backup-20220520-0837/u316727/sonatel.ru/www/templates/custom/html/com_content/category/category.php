<!--h1><?= $this->category->title ?></h1-->

<?php
$content = JHtml::_('content.prepare', $this->category->description, '', 'com_content.category');
$start = stripos($content, '<hr');
if ($start === false) {
	echo $content;
}
else {
	$end = strpos($content, '>', $start);
	//echo substr($content, 0, $start);	
	echo substr($content, $end + 1);

	
	  ?>
<div class="goods-list">
	<?php foreach ($this->lead_items as &$item) {
		$images = json_decode($item->images);
		$thumb = $images->image_intro;

		$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
		//$price = number_format($item->params->get('attr_product_price'), 0, '.', ' ') . ' руб.';
	?>
	<div class="item">
		<img src="<?= $thumb ?>" alt="<?= $item->title ?>" class="small" />    
		<div class="description">
			<h3><a href="<?= $link ?>"><?= $item->title ?></a> </h3>
			<?= $item->introtext ?>
			<a class="readmore" href="<?= $link ?>">Подробнее...</a>  
		</div>
	</div>

	<?php } ?>	
	
</div>
	<?php
	
}
?>

				<?php
			if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) :
				?>
				<div class="pagination">
					<?php if ($this->params->def('show_pagination_results',
									1)) :
						?>
						<p class="counter">
			<?php echo $this->pagination->getPagesCounter(); ?>
						</p>

					<?php endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
				</div>
	<?php endif; ?>
