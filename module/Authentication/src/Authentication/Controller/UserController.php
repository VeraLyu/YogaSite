<?php
namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Authentication\Form\UserForm;
use Authentication\Model\User;

class UserController extends AbstractActionController
{
    protected $userTable;
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

	protected function getTableGateway()
	{
	   if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('AuthenticationTableGateway');
       }
       return $this->userTable;
	}

	// @adminFunc
	// list all users in system
	public function indexAction()
	{
	    $viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);
        $result = $this->getTableGateway()->fetchAll();
        $filtered = $this->filterPlugin()->filterResultSet($result, array('password'));
		return $viewModel->setVariables($filtered);
	}

	// get user information including group and other settings
	// admin can retrieve without authentication
	public function detailAction()
	{
		// if it is admin or authorized user, return directly
		// if not, return error code
		$id = $this->params()->fromRoute('id');
	    $viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);
	    $result = $this->getTableGateway()->getObject($id);
	    $res = $this->filterPlugin()->filterResultSet(
	               $result,
	               array('password'));
	    return $viewModel->setVariables($res);
	}

	// create a user account in system
	public function addAction()
	{
	    $request = $this->getRequest();
	    $userObj = new User();
	    $userForm = new UserForm();
	    if (!$request->isPost())
	        //return array('form'=>$userForm);
	        throw new \Exception("Unsupported request");

	    $userForm->bind($userObj);
	    $userForm->setData($request->getPost());
	    if ($userForm->isValid())
	    {
	        $userObj->reformParam();
	        $this->getTableGateway()->saveObject($userObj);
	        return $this->redirect()->toRoute('users');
	    }
        throw new \Exception("Invalid Parameter");
	}

	// edit user password/role in system
	public function editAction()
	{
	    $request = $this->getRequest();
	    $userObj = new User();
	    $id = $this->params()->fromRoute('id');
	    $result = $this->getTableGateway()->getObject($id)->current();
	    $userForm = new UserForm();
	    $userForm->setData($result);
	    if (!$request->isPost() && $userForm->isValid())
	    {
	        $a =1;
	        return array('form'=>$userForm);
	    }
	        
	        //throw new \Exception("Unsupported request");

	    $userForm->bind($userObj);
	    $userForm->setData($request->getPost());
	    if ($userForm->isValid())
	    {
	        $userObj->reformParam();
	        $this->getTableGateway()->saveObject($userObj);
	        return $this->redirect()->toRoute('users');
	    }
        throw new \Exception("Invalid Parameter");
	}

	public function deleteAction()
	{
	    $id = $this->params()->fromRoute('id');
	    $viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);
	    return $this->redirect()->toRoute('');
	}
}