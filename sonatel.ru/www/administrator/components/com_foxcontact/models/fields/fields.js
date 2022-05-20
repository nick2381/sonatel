function EmailChooserChange(select)
{
	// Get the visibility state from the option class name
	var visibility = select.options[select.selectedIndex].className;
	// Get the child fieldset to be hidden
	var children = document.getElementById(select.id + "_children");
	// Set the visible state
	//children.style.visibility = visibility;
	children.style.display = visibility;
}

window.addEvent('domready',
function()
{
	selects = document.getElementsByTagName('select');

	for (var i = 0; i < selects.length; ++i)
	{
		var select = selects[i];
		if (select.getAttribute('class') == 'foxemailchooser')
		{
			select.onchange(select);
		}
	}

	/*
	for (var i in selects)
	{
	var select = selects[i];
	if (select.getAttribute('class') == 'foxemailchooser')
	{
	select.onchange(select);
	}
	}
	*/
});