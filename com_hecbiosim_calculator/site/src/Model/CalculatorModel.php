<?php
/**
 * @package    com_hecbiosim_calculator
 * @copyright  2025 HECBioSim Team
 * @license    MIT
 */

namespace Hecbiosim\Component\Hecbiosim_calculator\Site\Model;
// No direct access.
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Table;
use \Joomla\CMS\MVC\Model\ItemModel;
use \Joomla\CMS\Helper\TagsHelper;
use \Joomla\CMS\Object\CMSObject;
use \Joomla\CMS\User\UserFactoryInterface;
use \Hecbiosim\Component\Hecbiosim_calculator\Site\Helper\Hecbiosim_calculatorHelper;

/**
 * hecbiosim_calculator model.
 */
class CalculatorModel extends ItemModel
{
	protected function populateState()
	{
		$app  = Factory::getApplication('com_hecbiosim_calculator');
		$params       = $app->getParams();
		$params_array = $params->toArray();
		$this->setState('params', $params);
	}

	public function getItem ($id = null)
	{
	
	}
}
