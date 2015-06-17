<?php
namespace Authentication\Form;

use Zend\Form\Form;

class UserForm extends Form
{
	public function __construct($name = null, $options = array())
	{
		parent::__construct($name, $options);
		$this->add(array(
		        'type' => 'Authentication\Form\UserFieldset',
		        'name' => 'user-fieldset',
		        'options' => array(
		                'use_as_base_fieldset' => true
		        ),
		));
		
		$this->add(array(
		        'type' => 'submit',
		        'name' => 'submit',
		        'attributes' => array(
		                'value' => 'Create User',
		        ),
		));
	}
}