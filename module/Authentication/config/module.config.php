<?php
return array(
	'service_manager'=>array(
	   'factories' => array(
	            'AuthService' => 'Authentication\Factory\AuthServiceFactory',
	            'AuthenticationTableGateway' => 'Authentication\Factory\AuthTableFactory',
	            'GroupTableGateway' => 'Authentication\Factory\GroupTableFactory',
	   ),
    ),
	'controllers'=>array(
	   'invokables' => array(
	       'Authentication\Controller\Users' => 'Authentication\Controller\UserController',
	   ),   	
	),
	'router'=>array(
	   'routes' => array(
	       'users' => array(
	   	       'type' => 'literal',
	           'options' => array(
	       	       'route' => '/users',
	               'defaults' => array(
	           	       'controller' => 'Authentication\Controller\Users',
	                   'action' => 'index',
	               ),
	           ),
	           'may_terminate' => true,
	           'child_routes' => array(
	               'single' => array(
	           	       'type' => 'segment',
	                   'options' => array(
    	                   'route' => '/:action[/:id]',
	                       'defaults' => array(
	                   	       'controller' => 'Authentication\Controller\Users',
	                           'action' => 'index',
	                       ),
	                       'constraints' => array(
	                           'id' => '[1-9]\d*',
	                           'action' => 'index|detail|delete|edit|add',
	                       ),
	                   ),
	               ),
	               'add' => array(
	                   'type' => 'literal',
	                        'options' => array(
	                           'route' => '/add',
	                           'defaults' => array(
	                               'controller' => 'Authentication\Controller\Users',
	                               'action' => 'add',
	                           ),
	                        ),
	               ),
	           ),
	       ),
	   ),
	),
	'view_manager'=>array(
	        'template_path_stack' => array(
	           'Authentication'=>__DIR__ . '/../view',
	        ),
    ),
);