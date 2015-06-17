<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Response;

class LoginController extends AbstractActionController
{
	public function __construct($form)
	{
		$this->form = $form;
	}

	function getAuthService()
	{
		return $this->serviceLocator->get('AuthService');
	}

	public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity())
        {
            return $this->redirect()->toRoute('home');
        }
        $request = $this->getRequest();

        if(!$request->isPost())
        {
        	return array(
            	'form'=> $this->form,
        	);
        }
        else {
        	$data = $request->getPost();
        	$this->form->setData($data);
        	if ($this->form->isValid())
        	{
        		$adapter = $this->getAuthService()->getAdapter();
        		$response = new Response();

        		$adapter->setRequest($request);
        		$adapter->setResponse($response);
        		$res = $this->getAuthService()->authenticate();
        		if ($res->isValid()) {
        			return $this->redirect()->toRoute('home');
        		}

        		return $response;
        	}
        	throw new \Exception("Failed authentication");
        }
    }
}