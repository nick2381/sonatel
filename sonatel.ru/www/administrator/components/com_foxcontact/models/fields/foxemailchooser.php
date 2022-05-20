<?php defined('JPATH_PLATFORM') or die;
jimport("joomla.form.formfield");

JFormHelper::loadFieldClass("list");
class JFormFieldFoxEmailChooser extends JFormFieldList

//class JFormFieldFoxEmailChooser extends JFormField
{
	protected $type = "FoxEmailChooser";

	public function __construct($form = null)
	{
		parent::__construct($form);

		$this->com_name = basename(realpath(dirname(__FILE__) . "/../.."));
		$this->ext_name = substr($this->com_name, 4);

		$this->document = JFactory::getDocument();

		if (!isset($GLOBALS[$this->ext_name . "_fields_js_loaded"]))
		{
			$this->document->addScript(JUri::base(true) . '/components/' . $this->com_name . "/models/fields/fields.js");
			$GLOBALS[$this->ext_name . "_fields_js_loaded"] = true;
		}
	}

	/*
	overriding setup() prevent getInput() calling for some reasons.
	public function setup(&$element, $value, $group = null)
	{
	parent::setup($element, $value, $group);

	// Component configuration options
	// index.php?option=com_config&view=component&component=com_foxcontact&path=&tmpl=component
	$this->com_name = JRequest::getCmd("component");

	// Menu item options
	preg_match( '/option=(.*?)&/', $this->form->getValue("link"), $com_name);
	$this->com_name = isset($com_name[1]) ? $com_name[1] : "";

	// Module options
	$this->mod_name = $this->form->getValue("module");
	}
	*/

	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		$attr = "";

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true')
		{
			$attr .= ' disabled="disabled"';
		}

		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// Get the field options.
		$options = (array)$this->getOptions();

		$html[] = '<select onchange="EmailChooserChange(this);" onkeyup="EmailChooserChange(this);" name="' . $this->name . '[select]" id="jform_' . $this->fieldname . '" class="foxemailchooser">';
		foreach ($options as $option)
		{
			$selected = ($option->value == $this->value["select"]) ? $selected = 'selected="selected"' : "";
			$html[] = '<option value="' . $option->value . '" class="' . $option->class . '" ' . $selected . '>' . $option->text . '</option>';
		}
		$html[] = '</select>';


		$html[] = '<fieldset class="panelform" id="' . $this->id . '_children">';
		// Name
		$html[] = '<label for="jform_foxemailchooser_name" aria-invalid="false">' . JText::_("COM_FOXCONTACT_NAME") . '</label>';
		$html[] = '<input type="text" name="' . $this->name . "[name]" . '" id="' . $this->id . '_name' . '"' . ' value="'
		. htmlspecialchars($this->value["name"], ENT_COMPAT, 'UTF-8') . '"' . '/>';

		// Email
		$html[] = '<label for="jform_foxemailchooser_email" aria-invalid="false">' . JText::_("COM_FOXCONTACT_EMAIL_ADDRESS") . '</label>';
		$html[] = '<input type="text" name="' . $this->name . "[email]" . '" class="validate-email' . $class . '" id="' . $this->id . '_email' . '"' . ' value="'
		. htmlspecialchars($this->value["email"], ENT_COMPAT, 'UTF-8') . '"' . '/>';
		$html[] = "</fieldset>";

		return implode($html);
	}

}
