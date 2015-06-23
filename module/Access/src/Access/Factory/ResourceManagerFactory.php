<?php

namespace Access\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Access\Model\ResourceManager;

class ResourceManagerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$cache = $serviceLocator->get('Access\Cache');
		$modules = $serviceLocator->get('ModuleManager')->getLoadedModules();
		return new ResourceManager($cache, $modules);
	}
}