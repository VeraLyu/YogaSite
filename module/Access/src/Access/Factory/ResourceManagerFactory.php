<?php

namespace Access\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Access\Model\ResourceManager;

class ResourcecacheFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$sm = $serviceLocator->getServiceLocator();
		$cache = $sm->get('Access\Cache');
		$modules = $sm->get('ModuleManager')->getLoadedModules();
		return new ResourceManager($cache, $modules);
	}
}