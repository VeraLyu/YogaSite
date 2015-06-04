<?php
namespace Application\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class AuthServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
		
		$authAdapter = new AuthAdapter($dbAdapter, 'users','username', 'password');
		$authService = new AuthenticationService();
		$authService->setAdapter($authAdapter);
		
		return $authService;
	}
}