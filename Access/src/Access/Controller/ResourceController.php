<?php
namespace Access\Controller;

use Zend\Mvc\Controller\AbstractActionController;

use Access\Model\ResourceManagerInterface;

class Resource extends AbstractActionController
{
	protected $resourceManager;

	public function __construct(ResourcManagerInterface $resourceManager)
	{
		$this->resourceManager = $resourceManager;
	}
	public function indexAction()
	{
		//get all available quriable resources
		return Array();
	}

	public function detailAction()
	{
		return Array();
	}
}