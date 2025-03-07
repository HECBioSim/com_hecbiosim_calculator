<?php
/**
 * @package    com_hecbiosim_calculator
 * @copyright  2025 HECBioSim Team
 * @license    MIT
 */

defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Hecbiosim\Component\Hecbiosim_calculator\Administrator\Extension\Hecbiosim_calculatorComponent;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;


/**
 * The Hecbiosim_calculator service provider.
 */
return new class implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 */
	public function register(Container $container)
	{

		$container->registerServiceProvider(new CategoryFactory('\\Hecbiosim\\Component\\Hecbiosim_calculator'));
		$container->registerServiceProvider(new MVCFactory('\\Hecbiosim\\Component\\Hecbiosim_calculator'));
		$container->registerServiceProvider(new ComponentDispatcherFactory('\\Hecbiosim\\Component\\Hecbiosim_calculator'));
		$container->registerServiceProvider(new RouterFactory('\\Hecbiosim\\Component\\Hecbiosim_calculator'));

		$container->set(
			ComponentInterface::class,
			function (Container $container)
			{
				$component = new Hecbiosim_calculatorComponent($container->get(ComponentDispatcherFactoryInterface::class));

				$component->setRegistry($container->get(Registry::class));
				$component->setMVCFactory($container->get(MVCFactoryInterface::class));
				$component->setCategoryFactory($container->get(CategoryFactoryInterface::class));
				$component->setRouterFactory($container->get(RouterFactoryInterface::class));

				return $component;
			}
		);
	}
};
