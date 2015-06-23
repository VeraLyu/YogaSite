<?php
namespace Access\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Access\Controller\ControllerresourceController;


class ControllerresourceControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$sm = $serviceLocator->getServiceLocator();
		$resManager = $sm->get('Access\Model\ResourceManager');
		return new ControllerresourceController($resManager);
	}
}