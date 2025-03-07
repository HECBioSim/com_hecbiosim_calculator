<?php
/**
 * @package    com_hecbiosim_calculator
 * @copyright  2025 HECBioSim Team
 * @license    MIT
 */

namespace Hecbiosim\Component\Hecbiosim_calculator\Administrator\Extension;

defined('JPATH_PLATFORM') or die;

use Hecbiosim\Component\Hecbiosim_calculator\Administrator\Service\Html\HECBIOSIM_CALCULATOR;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Association\AssociationServiceInterface;
use Joomla\CMS\Association\AssociationServiceTrait;
use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\CMS\Tag\TagServiceTrait;
use Psr\Container\ContainerInterface;
use Joomla\CMS\Categories\CategoryServiceInterface;

/**
 * Component class for hecbiosim_calculator
 */
class Hecbiosim_calculatorComponent extends MVCComponent implements RouterServiceInterface, BootableExtensionInterface, CategoryServiceInterface
{
	use AssociationServiceTrait;
	use RouterServiceTrait;
	use HTMLRegistryAwareTrait;
	use CategoryServiceTrait, TagServiceTrait {
		CategoryServiceTrait::getTableNameForSection insteadof TagServiceTrait;
		CategoryServiceTrait::getStateColumnForSection insteadof TagServiceTrait;
	}

	/** @inheritdoc  */
	public function boot(ContainerInterface $container)
	{
		$db = $container->get('DatabaseDriver');
		$this->getRegistry()->register('hecbiosim_calculator', new HECBIOSIM_CALCULATOR($db));
	}

	
         /**
         * Returns the table for the count items functions for the given section.
	 *
	 * @param   string    The section
	 *
	 * * @return  string|null
	 */
	    protected function getTableNameForSection(string $section = null)            
	{
	}
	
     /**
     * Adds Count Items for Category Manager.
     *
     * @param   \stdClass[]  $items    The category objects
     * @param   string       $section  The section
     *
     * @return  void
     */
    public function countItems(array $items, string $section)
    {
	}
}
