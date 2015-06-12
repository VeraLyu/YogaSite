<?php
namespace Application\Form;

use Zend\Form\Form;

class ApplicationForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('application');
	
		$this->add(array(
				'name' => 'id',
				'type' => 'Hidden',
		));
		$this->add(array(
				'name' => 'user',
				'type' => 'Text',
				'options' => array(
						'label' => 'User',
				),
		));
		$this->add(array(
				'name' => 'password',
				'type' => 'Text',
				'options' => array(
						'label' => 'Password',
				),
		));
		$this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
						'value' => 'Go',
						'id' => 'submitbutton',
				),
		));
	}
}
