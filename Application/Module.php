<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Authentication\Lib\Auth;


class Module
{
 //   public function onBootstrap(MvcEvent $e)
 //   {
 //       $eventManager        = $e->getApplication()->getEventManager();
 //       $eventManager->attach('render', array($this, 'registerJsonStrategy'), 100);
 //   }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function registerJsonStrategy($e)
    {
    	$app          = $e->getTarget();
    	$locator      = $app->getServiceManager();
    	$view         = $locator->get('Zend\View\View');
    	$jsonStrategy = $locator->get('ViewJsonStrategy');

    	// Attach strategy, which is a listener aggregate, at high priority
    	$view->getEventManager()->attach($jsonStrategy, 100);
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(
                MvcEvent::EVENT_DISPATCH, function (MvcEvent $event)
                {
                    $auth = $event->getApplication()
                                  ->getServiceManager()
                                  ->get('AuthService');
                    if (!$auth->hasIdentity())
                    {
                        
                        $adapter = $auth->getAdapter();
                        $response = $event->getResponse();
                        $adapter->setRequest($event->getRequest());
                        $adapter->setResponse($response);
                        $res = $auth->authenticate();
                        if (!$res->isValid()) {
                            return $response;
                        }
                     }
                     //\Zend\Debug\Debug::dump($auth->getIdentity());
                }, 100);

        $eventManager->attach(
                MvcEvent::EVENT_DISPATCH, function (MvcEvent $event)
                {
                    $request = $event->getRequest();
                    //\Zend\Debug\Debug::dump($request);
                    if ($request->getHeaders('Content-type') &&
                            $request->getHeaders(
                                    'Content-type')->getMediaType()== 'application/json')
                    {
                        //\Zend\Debug\Debug::dump($request->getContent());
                        $values = get_object_vars(
                                Json::decode($request->getContent ()));
                        //\Zend\Debug\Debug::dump($values);
                        foreach ($values as $key=>$val)
                            $request->getPost()->set($key, $val);
                    }
                }, 100);

    }
}
