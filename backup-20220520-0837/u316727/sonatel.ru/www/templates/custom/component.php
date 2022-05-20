<?php
defined('_JEXEC') or die;
include 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
	<jdoc:include type="head" />
	<?php include 'assets-head.php' ?>
</head>
<body class="contentpane">
<jdoc:include type="message" />
<jdoc:include type="component" />
<?php include 'assets-footer.php' ?>
<jdoc:include type="modules" name="debug" />
</body>
</html>
