<?php
defined('_JEXEC') or die ('Restricted access'); ?>

<a name="<?php echo("mid_" . $module->id); ?>"></a>

<div class="foxcontainer<?php echo($params->get("moduleclass_sfx")); ?>" style="width:<?php echo($params->get("form_width", "100") . $params->get("form_unit", "%")); ?> !important;">

<?php
// Page Subheading if needed
if (!empty($page_subheading))
	echo("<h2>" . $page_subheading . "</h2>" . PHP_EOL);
?>

<?php
/* Don't remove the following code, or you will loose system messages too, like
"Invalid field: email" or "Your messages has been received" and so on.
If you have problems related to language files, fix your language file instead. */
if (count($messages))
	{
	echo('<ul class="fox_messages">');
	foreach ($messages as $message)
		{
		echo("<li>" . $message . "</li>");
		}
	echo("</ul>");
	}
?>

<?php if (!empty($form_text)) { ?>
<form enctype="multipart/form-data" class="foxform" action="<?php echo($link); ?>" method="post">	
	<?php echo($form_text); ?>
</form>
<?php } ?>

</div>

