
<?= JHtml::_('content.prepare', $this->category->description, '', 'com_content.category') ?>

<div class="product-list">
	<?php foreach ($this->items as &$item) {
		$images = json_decode($item->images);
		$thumb = $images->image_intro;

		$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
		$price = number_format($item->params->get('attr_product_price'), 0, '.', ' ') . ' руб.';
	?>
		<div class="product-item">
			<a class="product-thumb" href="<?= $link ?>"><img
						src="<?= $thumb ?>"
						alt="<?= $item->title ?>" /></a>
				<div class="product-descr">
						<div class="price"><?= $price ?></div>
						<a href="<?= $link ?>" class="product-name"><?= $item->title ?></a>
							<?= $item->introtext ?>
				</div>
		</div>
	<?php } ?>
</div>

