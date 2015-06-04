<?php
namespace Application\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Form\ApplicationForm;
use Application\Controller\LoginController;

class LoginControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$form = new ApplicationForm('login');
		$form->setAttribute('submit', 'Login');
		return new LoginController($form);
	}
}