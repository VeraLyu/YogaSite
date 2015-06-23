<?php
namespace Authentication\Form;

use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;

use Authentication\Model\User;

class UserFieldset extends Fieldset
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->add(array(
                'type' => 'hidden',
                'name' => 'userid',
        ));
        $this->add(array(
                'type' => 'text',
                'name' => 'username',
                'options' => array(
                    'label' => 'User Name'
                )
        ));
        $this->add(array(
                'type' => 'hidden',
                'name' => 'groupid',
        ));
        $this->add(array(
                'type' => 'Zend\Form\Element\Password',
                'name' => 'password',
                'options' => array(
                        'label' => 'Password'
                )
        ));
    
    }
}