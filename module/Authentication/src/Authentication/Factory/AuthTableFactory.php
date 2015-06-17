<?php
namespace Authentication\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;

use Application\Model\AbstractTable;

class AuthTableFactory implements  FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $gateway = new TableGateway('users', $adapter);
    	return new AbstractTable($gateway, 'userid');
    }
}