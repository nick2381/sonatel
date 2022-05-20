<?php
defined('_JEXEC') or die;

//array_shift($list);
unset($list[1]);
//if (isset($list[1])) {
//	$list[1]->link = null;
//}
?>

<div class="breadcrumbs<?php echo $moduleclass_sfx; ?>">
<?php 

	// Get rid of duplicated entries on trail including home page when using multilanguage
	for ($i = 0; $i < $count; $i ++)
	{
		if ($i == 1 && !empty($list[$i]->link) && !empty($list[$i-1]->link) && $list[$i]->link == $list[$i-1]->link)
		{
			unset($list[$i]);
		}
	}

	// Find last and penultimate items in breadcrumbs list
	end($list);
	$last_item_key = key($list);
	prev($list);
	$penult_item_key = key($list);

	// Generate the trail
	foreach ($list as $key=>$item) :
	// Make a link if not the last item in the breadcrumbs
	$show_last = $params->get('showLast', 1);
	if ($key != $last_item_key)
	{
		// Render all but last item - along with separator
		if (!empty($item->link))
		{
			echo '<a href="' . $item->link . '" class="pathway">' . $item->name . '</a>';
		}
		else
		{
			echo '<span>' . $item->name . '</span>';
		}

		if (($key != $penult_item_key) || $show_last)
		{
			echo ' / ';
		}

	}
	elseif ($show_last)
	{
		// Render last item if reqd.
		echo '<span>' . $item->name . '</span>';
	}
	endforeach; ?>
</div>

<p>