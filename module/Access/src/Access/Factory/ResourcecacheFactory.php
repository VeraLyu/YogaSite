<?php
namespace Access\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Cache\StorageFactory;


class ResourcecacheFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$cache   = StorageFactory::factory(array(
				'adapter' => array(
						'name' => 'filesystem',
						'options' => array(
								'ttl' => 3600,
								'cache_dir' => __DIR__ . '/../../../../../data/cache',
						),
				),
				'plugins' => array(
						// Don't throw exceptions on cache errors
						'exception_handler' => array(
								'throw_exceptions' => false
						),
						'Serializer',
				)
		));
		return $cache;
	}
}