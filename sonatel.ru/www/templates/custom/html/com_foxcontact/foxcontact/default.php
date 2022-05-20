<?php
defined('_JEXEC') or die('Restricted access');

if ($this->cparams->get('menu-meta_description')) {
	$this->document->setDescription($this->cparams->get('menu-meta_description'));
}
if ($this->cparams->get('menu-meta_keywords')) {
	$this->document->setMetadata('keywords', $this->cparams->get('menu-meta_keywords'));
}
if ($this->cparams->get('robots')) {
	$this->document->setMetadata('robots', $this->cparams->get('robots'));
}

$wholemenu = $this->Application->getMenu();
$activemenu = $wholemenu->getActive();
$cid = $activemenu->id;

echo "<a name=\"cid_$cid\"></a>";
echo '<div class="foxcontainer' . /*$this->cparams->get('pageclass_sfx') .*/ '" style="width:' . $this->cparams->get("form_width",
		"550") . $this->cparams->get("form_unit", "px") . ' !important;">';

if ($this->cparams->get('show_page_heading')) {
	echo "<h1>" . $this->escape($this->cparams->get('page_heading')) . "</h1>" . PHP_EOL;
}
//$page_subheading = $this->cparams->get("page_subheading", "");
//if (!empty($page_subheading)) {
//	echo "<h2>" . $page_subheading . "</h2>" . PHP_EOL;
//}

$xml = JFactory::getXML(JPATH_ADMINISTRATOR . "/components/" . $GLOBALS["com_name"] . "/" . $GLOBALS["ext_name"] . ".xml");

if (count($this->messages)) {
	echo '<ul class="fox_messages">';
	//echo("<li>" . $this->cparams->get("missing_fields_text") . "</li>");
	foreach ($this->messages as $message) {
		echo "<li>" . $message . "</li>";
	}
	echo "</ul>";
}

if (!empty($this->FormText)) {
	?>
	<form enctype="multipart/form-data" method="post"
				id="FoxForm" name="FoxForm"
				class="foxform<?= $this->cparams->get('pageclass_sfx') ?>"
				action="<?php echo($_SERVER['REQUEST_URI'] . '#cid_' . $cid); ?>">
		<fieldset>
			<legend><?= $this->cparams->get('page_subheading', '') ?></legend>
			<?= $this->FormText ?>
			</fieldset>
	</form>
	<?php
}
echo '</div>';
