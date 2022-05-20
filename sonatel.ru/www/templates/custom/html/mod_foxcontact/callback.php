<?php
defined('_JEXEC') or die('Restricted access'); 
?>

<a name="<?= 'mid_' . $module->id ?>"></a>
<?php
if (!empty($page_subheading))
	echo '<h2>' . $page_subheading . '</h2>' . PHP_EOL;
?>

<?php
if (count($messages)) {
	echo '<ul class="fox_messages">';
	foreach ($messages as $message)
	{
		echo '<li>' . $message . '</li>';
	}
	echo '</ul>' ;
}
	
$form_text = str_replace(
		array('</div>', 'class="foxtext"'), array('', 'type="phone" id="phone-num" size=20'), $form_text);
$form_text = preg_replace('{<div.*?>}si', '', $form_text);

?>

<?php if (!empty($form_text)) { ?>
<form id="phone-num-feedback" 
			enctype="multipart/form-data" class="foxform" action="<?= $link ?>" method="post">	
	<?= $form_text ?>
</form>
<?php } ?>

