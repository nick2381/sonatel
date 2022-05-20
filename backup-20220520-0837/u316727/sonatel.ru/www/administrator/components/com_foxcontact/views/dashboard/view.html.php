<?php defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class FoxContactViewDashboard extends JView
{
	protected $com_name;

	public function display($tpl = null)
	{
		$this->com_name = JFactory::getApplication()->scope;

		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	protected function addToolBar()
	{
		JFactory::getLanguage()->load($this->com_name . ".sys");

		JToolBarHelper::title(JText::_("COM_FOXCONTACT_MENU"), "foxcontact");
		JToolBarHelper::preferences("com_foxcontact");
	}

	protected function setDocument()
	{

		$document = JFactory::getDocument();
		$document->setTitle(JText::_("COM_FOXCONTACT_MENU"));
		$document->addStyleSheet(JUri::base(true) . '/components/' . $this->com_name . "/css/component.css");
	}

}
