<?php
namespace Authentication\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\Http as AuthAdapter;

use Authentication\Mapper\DbResolver;


class AuthServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
	    $config = array(
	            'accept_schemes' => 'digest',
	            'realm'          => 'My Web Site',
	            'digest_domains' => '/',
	            'nonce_timeout'  => 3600,
	    );
		$dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

		$authAdapter = new AuthAdapter($config);
		$resolver = new DbResolver($dbAdapter, 'users', 'username', 'password');
		$authAdapter->setDigestResolver($resolver);
		$authService = new AuthenticationService();
		$authService->setAdapter($authAdapter);

		return $authService;
	}
}