<?php
/*
* @Author Character
* @Url 
* @License http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
* @Version 1.2.0
*/
if (!defined('_JEXEC')) die('Direct Access is not allowed.');
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;

class plgSystemCharacter_Meta_Counter extends CMSPlugin {

	protected $autoloadLanguage = true;

	function __construct($event,$params){
		parent::__construct($event,$params);
	}

	function onBeforeRender() {

		if(!Factory::getApplication()->isClient('administrator')) {
			return;
		}

		if (Factory::getDocument()->getType() !== 'html')
		{
			return;
		}

		$jinput = Factory::getApplication()->input;
		$option = $jinput->get('option');
		$view = $jinput->get('view');
		$task = $jinput->get('task');
		$controller = $jinput->get('controller');

		$execute = false;

		// Joomla support
		if (($option == 'com_content' && $view == 'article') || ($option == 'com_categories' && $view == 'category') || ($option == 'com_menus' && $view == 'item')) {
			$execute = true;
		}

		// Virtuemart support
		if ($option == 'com_virtuemart' && ($view == 'category' || $view == 'product')) {
			$execute = true;
		}

		// JoomShopping support
		if ($option == 'com_jshopping' && ($controller == 'categories' || $controller == 'products')) {
			$execute = true;
		}

		if (!$execute) {
			return;
		}

		$plg_params['params'] = $this->params;
		$plg_params['PLG_CHARACTER_META_COUNTER_CHARACTERS_LEFT'] = JText::_('PLG_CHARACTER_META_COUNTER_CHARACTERS_LEFT');

		$document = Factory::getDocument();
		$document->addScript('/plugins/'.$this->_type.'/'.$this->_name.'/assets/js/character_meta_counter.js');
		$js= 'let plg_system_character_meta_counter_params = '.json_encode($plg_params).';';
		$document->addScriptDeclaration($js);
	}
}
