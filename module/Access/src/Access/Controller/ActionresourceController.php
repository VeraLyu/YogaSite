<?php
namespace Access\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class ActionresourceController extends AbstractActionController
{
	protected $acceptCriteria = array(
			'Zend\View\Model\JsonModel' => array(
					'application/json',
			),
			'Zend\View\Model\FeedModel' => array(
					'application/rss+xml',
			),
			'Zend\View\Model\ViewModel' => array(
					'text/html',
			),
	);
	public function __construct($resourceManager)
	{
		$this->resourceManager = $resourceManager;
	}

	public function indexAction()
	{
		//get all available quriable resources
		$viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);
		$module = $this->params()->fromRoute('moduleName');
		$controller = $this->params()->fromRoute('controllerName');
		return $viewModel->setVariables(
				array($this->resourceManager->getControllers($module)));
	}

	public function detailAction()
	{
		
	}
}