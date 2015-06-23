<?php
return array(
	'service_manager'=> array(
		'factories' => array(
			'Access\Cache' => 'Access\Factory\ResourcecacheFactory',
			'Access\Model\ResourceManager' => 'Access\Factory\ResourceManagerFactory',
		),
	),
    'controllers' => array(
		'factories' => array(
			'Access\Controller\Modules' => 'Access\Factory\ModuleResourceControllerFactory',
			'Access\Controller\Controllers' => 'Access\Factory\ControllerResourceControllerFactory',
			'Access\Controller\Actions' => 'Access\Factory\ActionControllerFactory',
        ),
    ),

    'router' => array(
        'routes' => array(
            'access' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/modules',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Access\Controller',
                        'controller'    => 'Modules',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'module' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/:moduleName',
                            'constraints' => array(
                                'moduleName' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Access\Controller',
                                'controller'    => 'Controllers',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'controller' => array(
                                'type'    => 'segment',
                                'options' => array(
                                    'route'    => '/:controllerName',
                                    'constraints' => array(
                                        'controllerName' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        '__NAMESPACE__' => 'Access\Controller',
							'controller'    => 'Actions',
							'action' => 'index',
						),
					),
					'may_terminate' => true,
					'child_routes' => array(
						'controller' => array(
							'type'    => 'Segment',
							'options' => array(
								'route'    => '/:actionName[/:action]',
								'constraints' => array(
									'actionName' => '[a-zA-Z][a-zA-Z0-9_-]*',
									'action' => 'get|edit'
								),
								'defaults' => array(
									'action' => 'get',
								),
							),
						),
					),
				),
			),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'access' => __DIR__ . '/../view',
        ),
    ),
);
