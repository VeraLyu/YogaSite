<?php
namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class UserController extends AbstractActionController
{
	// @adminFunc
	// list all users in system
	public function listAction()
	{
		
	}

	// get user information including group and other settings
	// admin can retrieve without authentication
	public function detailAction()
	{
		// if it is admin or authorized user, return directly
		// if not, return error code
	}

	// create a user account in system
	public function createAction()
	{

	}

	// edit user password in system
	public function editAction()
	{
		
	}
}