<?php
defined('_JEXEC') or die;
?>

<div class="apartments-home">
	<h2> <?= $module->title ?> </h2>
	<ul class="blocks-4">
		<?php
		foreach ($list as $item) {
			//$price = number_format($item->params->get('attr_product_price'), 0, '.', ' ');
			$images = json_decode($item->images);
			$thumb = $images->image_intro;			
			?>
			<li>
				<a href="<?= $item->link ?>"><img
						src="<?= $thumb ?>"
						alt="<?= htmlspecialchars($item->title) ?>" /></a>
				<a href="<?= $item->link ?>" class="ap-name"><?= $item->title ?></a>
			</li>
		<?php } ?>
	</ul>
</div>
