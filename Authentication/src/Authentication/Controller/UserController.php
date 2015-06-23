<?php
namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Authentication\Form\UserForm;
use Authentication\Model\User;

class UserController extends AbstractActionController
{
    protected $userTable;

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
	    $acceptCriteria = $this->viewSelectPlugin()->getAcceptedCriteria();
	    $viewModel = $this->acceptableViewModelSelector($acceptCriteria);
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
		$acceptCriteria = $this->viewSelectPlugin()->getAcceptedCriteria();
	    $viewModel = $this->acceptableViewModelSelector($acceptCriteria);
	    $result = $this->getTableGateway()->getObject($id);
	    $res = $this->filterPlugin()->filterResultSet(
	               $result,
	               array('password'));
	    return $viewModel->setVariables($res);
	}

	// create a user account in system
	public function addAction()
	{
	    $acceptCriteria = $this->viewSelectPlugin()->getAcceptedCriteria();
	    $viewModel = $this->acceptableViewModelSelector($acceptCriteria);
	    $request = $this->getRequest();
	    $userObj = new User();
	    if (!$request->isPost())
	        throw new \Exception("Unsupported request");

	    $data = $request->getPost()->toArray();
	    if ($userObj->isValid($data))
	    {
	        $userObj->exchangeArray($data);
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
	    //$res = $this->filterPlugin()->filterResultSet(
	    //        $result,
	    //        array('password'));
	    //$userForm = new UserForm();
	    //$userForm->bind($result);
	    if (!$request->isPost())
	    {
	        //return array('id'=>$id, 'form'=>$userForm);
	        throw new \Exception("Unsupported request");
	    }
        $data = $request->getPost()->toArray();
        $data['userid'] = (int) $id;
	    //$userForm->bind($userObj);
	    //$userForm->setData($request->getPost());
	    \Zend\Debug\Debug::dump($data);
	    if ($userObj->isValid($data))
	    {
	        $userObj->exchangeArray($data);
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
	    $this->getTableGateway()->deleteObject($id);
	    return $this->redirect()->toRoute('users');
	}
}