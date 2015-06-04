<?php
namespace Access\Controller;

use Access\Model\ResourceManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class ControllerresourceController extends AbstractActionController
{
	protected $resourceManager;
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

	public function __construct(ResourceManagerInterface $resourceManager)
	{
		$this->resourceManager = $resourceManager;
	}

	public function indexAction()
	{
		//get all available quriable resources
		$module = $this->params()->fromRoute('moduleName');
		$viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);
		$methodResult = $this->resourceManager->getControllers($module);
		return $viewModel->setVariables($methodResult);
	}
}