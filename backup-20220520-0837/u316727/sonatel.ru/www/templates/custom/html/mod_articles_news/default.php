<?php
defined('_JEXEC') or die;

//(int) JHTML::date($item->created, 'd') . ' ' .
//				JText::_('MSG_MONTH' . JHTML::date($item->created, 'm')) . ' ' .
//				JHTML::date($item->created, 'Y')
	
?>

<div class="news content-list">
	<h2>Последние новости:</h2>

	<?php foreach ($list as $item) { ?>
	<div class="item">
		<div class="description">
			<div class="date"><?= JHTML::date($item->created, 'd.m.Y') ?></div>
			<h3><a href="<?= $item->link ?>"><?= $item->title ?></a> </h3>
			<?= $item->introtext ?>			
		</div>
	</div>
	<?php } ?>
</div>
