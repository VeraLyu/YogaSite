<?php
namespace Access\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class Resource extends AbstractActionController
{
	public function __construct($parent)
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