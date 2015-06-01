<?php
namespace Access\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ListControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$realServiceLocator = $serviceLocator->getServiceLocator();
		$config = $realServiceLocator->get('Config');
		return new ListController($postService);
	}
}