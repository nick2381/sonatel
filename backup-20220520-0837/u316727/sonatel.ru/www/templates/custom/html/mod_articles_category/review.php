<?php
defined('_JEXEC') or die;
?>

<h3><?= $module->title ?></h3>

<div class="reviews-list">

	<?php foreach ($list as $item) { ?>

		<div class="review">
			<div class="caption"> <?= str_replace('|', '<br>',$item->title) ?></div>
			<div class="review-cont">
				<?= $item->introtext ?>
			</div>
		</div>

	<?php } ?>

</div>