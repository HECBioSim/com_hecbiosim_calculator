<?php
/**
 * @package    com_hecbiosim_calculator
 * @copyright  2025 HECBioSim Team
 * @license    MIT
 */

namespace Hecbiosim\Component\Hecbiosim_calculator\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;

/**
 * hecbiosim_calculator master display controller.
 */
class DisplayController extends BaseController
{
	/**
	 * The default view.
	 *
	 * @var    string
	 */
	protected $default_view = '//XXX_DEFAULT_VIEW_XXX';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link InputFilter::clean()}.
	 *
	 * @return  BaseController|boolean  This object to support chaining.
	 */
	public function display($cachable = false, $urlparams = array())
	{
		return parent::display();
	}
}
