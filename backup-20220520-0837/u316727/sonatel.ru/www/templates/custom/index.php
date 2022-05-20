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
<body>

	<div class="wrapper">
		<div class="feedback">Задать вопрос</div>	
							
		<header class="header">
			<a href="/" class="logo"></a>
			<div class="head-right">
				<jdoc:include type="modules" name="atomic-topquote" />
			</div>
			<div class="search">
				<jdoc:include type="modules" name="atomic-search" />
			</div>

			<nav class="navigation">
				<jdoc:include type="modules" name="atomic-topmenu" />
			</nav>
    </header>

		<?php if ($isMainPage) { ?>

			<jdoc:include type="component" />
			<jdoc:include type="modules" name="atomic-bottommiddle" />

			<?php
		}
		else {
			?>

			<div class="container">

				<jdoc:include type="modules" name="intro" />
				
				<jdoc:include type="modules" name="atomic-sidebar" />				

				<div class="content">
					
					<jdoc:include type="message" />
					<jdoc:include type="modules" name="breadcrumbs" />
					<jdoc:include type="component" />
					<jdoc:include type="modules" name="pagecontent" />
					<jdoc:include type="modules" name="contactscontent" />

					<jdoc:include type="modules" name="mainnews" />
				</div>

			</div>

		<?php } ?>

	</div>

	<footer>
		<div class="footer-wrapper">
			<div class="copyright"><jdoc:include type="modules" name="bottomline" /></div>
			<div class="footer-contacts">
				<jdoc:include type="modules" name="counters" />
			</div>
		</div>
	</footer>

	<?php include 'assets-footer.php' ?>
<jdoc:include type="modules" name="debug" />
</body>
</html>