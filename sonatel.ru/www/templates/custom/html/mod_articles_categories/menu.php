<?php
defined('_JEXEC') or die;

$tmp = explode('?', $_SERVER['REQUEST_URI'], 2);
$currentPath = $tmp[0];
$articleCategoryId = JRequest::getVar('view') == 'article' ? JRequest::getVar('catid') : null;

?>

<div class="left-sidebar">
		<div class="services-nav">
        
		<ul>
			<?php foreach ($list as $item) { ?>
				<li><a href="<?= JRoute::_(ContentHelperRoute::getCategoryRoute($item->id)) ?>"><?= $item->title ?></a>
					<ul>
						<?php
						$subcategories = $item->getChildren();
						//print_r($item->getItems());
						
						foreach ($item->getChildren() as $category) {
							$subcategories = $category->getChildren();
	
						//var_dump($subcategories);
						
							// check active category
							$isActive = $currentPath == JRoute::_(ContentHelperRoute::getCategoryRoute($category->id));
							if (!$isActive) {
								foreach ($subcategories as $subcategory) {
									if (!empty($articleCategoryId)) {
										if ($articleCategoryId == $subcategory->id) {
											$isActive = true;
										}
									}
									else if ($currentPath == JRoute::_(ContentHelperRoute::getCategoryRoute($subcategory->id))) {
										$isActive = true;
									}
								}
							}
							?>
							<li class="<?= $isActive ? 'open' : '' ?>"><a
									href="<?= JRoute::_(ContentHelperRoute::getCategoryRoute($category->id)) ?>"><?= $category->title ?></a>
									<?php if ($isActive && count($subcategories) > 0) { ?>
									<ul>
										<?php
										foreach ($subcategories as $subcategory) {
											// check active subcategory
											if (!empty($articleCategoryId)) {
												$isSubcategoryActive = $articleCategoryId == $subcategory->id;
											}
											else {
												$isSubcategoryActive = $currentPath == JRoute::_(ContentHelperRoute::getCategoryRoute($subcategory->id));
											}
											?>
											<li class="<?= $isSubcategoryActive ? 'active' : '' ?>"><a
													href="<?= JRoute::_(ContentHelperRoute::getCategoryRoute($subcategory->id)) ?>"><?= $subcategory->title ?></a></li>
											<?php } ?>
									</ul>
								<?php } ?>
							</li>
						<?php } ?>
					</ul>
				</li>
			<?php } ?>
		</ul>

	</div>
</div>
