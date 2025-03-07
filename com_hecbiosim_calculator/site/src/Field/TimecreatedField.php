<?php
/**
 * @package    com_hecbiosim_calculator
 * @copyright  2025 HECBioSim Team
 * @license    MIT
 */

namespace Hecbiosim\Component\Hecbiosim_calculator\Site\Field;

defined('JPATH_BASE') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Form\FormField;
use \Joomla\CMS\Date\Date;

/**
 * Supports an HTML select list of categories
 */
class TimecreatedField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'timecreated';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();

		$time_created = $this->value;

		if (!strtotime($time_created))
		{
			$time_created = Factory::getDate()->toSql();
			$html[]       = '<input type="hidden" name="' . $this->name . '" value="' . $time_created . '" />';
		}

		$hidden = (boolean) $this->element['hidden'];

		if ($hidden == null || !$hidden)
		{
			$jdate       = new Date($time_created);
			$pretty_date = $jdate->format(Text::_('DATE_FORMAT_LC2'));
			$html[]      = "<div>" . $pretty_date . "</div>";
		}

		return implode($html);
	}
}
