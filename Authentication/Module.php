<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Access for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Authentication;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Json\Json;


class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        // And now we let the magic happen (this is the bit we
        // will insert)
        $eventManager->attach(
            MvcEvent::EVENT_ROUTE,
            function (MvcEvent $event)
            {
        	    $dbAdapter = $event->getApplication()
                    ->getServiceManager()
                    ->get('Zend\Db\Adapter\Adapter');

        	    $this->_prepareGroupDb($dbAdapter);
        	    $this->_prepareUserDb($dbAdapter);
        	});
    }

    protected function _prepareGroupDb($dbAdapter)
    {
        $result = $dbAdapter->query("
                    SELECT 1
                    FROM information_schema.tables
                    WHERE table_type='base table' AND table_name='groups'
                    ")->execute();
        
        if ($result->current() === false) {
            try {
                $result = $dbAdapter->query("
                                CREATE TABLE `groups` (
                                `groupid` INT(10) NOT NULL auto_increment,
                                `groupname` VARCHAR(20) NOT NULL,
                                PRIMARY KEY (`groupid`)
                                )")->execute();
        
                $dbAdapter->query("
                                INSERT INTO `groups` VALUES
                                (1, 'admin')
                                ")->execute();
        
                $dbAdapter->query("
                                INSERT INTO `groups` VALUES
                                (1000, 'guest')
                                ")->execute();
            } catch (\Exception $e) {
                \Zend\Debug\Debug::dump($e->getMessage());
            }
        }
    }

    protected function _prepareUserDb($dbAdapter)
    {
        $result = $dbAdapter->query("
                    SELECT 1
                    FROM information_schema.tables
                    WHERE table_type='base table' AND table_name='users'
                    ")->execute();

        if ($result->current() === false) {
            try {
                $result = $dbAdapter->query("
                                CREATE TABLE `users` (
                                `userid` INT(10) NOT NULL auto_increment,
                                `username` VARCHAR(20) NOT NULL,
                                `password` CHAR(32) NOT NULL,
                                `groupid` INT(10) NOT NULL,
                                PRIMARY KEY (`userid`),
                                FOREIGN KEY (`groupid`) REFERENCES groups(groupid)
                                )")->execute();

                $dbAdapter->query("
                                INSERT INTO `users` VALUES
                                (1, 'admin', '". md5("admin:My Web Site:admin"). "', 1)
                                ")->execute();

                $dbAdapter->query("
                                INSERT INTO `users` VALUES
                                (1000, 'guest', '". md5("guest:My Web Site:guest"). "', 1000)
                                ")->execute();
            } catch (\Exception $e) {
                \Zend\Debug\Debug::dump($e->getMessage());
            }
        }
    }
}
