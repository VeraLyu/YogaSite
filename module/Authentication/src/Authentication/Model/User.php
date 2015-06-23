<?php
namespace Authentication\Model;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

use Application\Util\Utils;


class User implements InputFilterAwareInterface
{
    public $userid;
    public $username;
    public $password;
    public $groupid;
    protected $_inputFilter;

    public function __construct()
    {
        // Default user group is guest
    	$this->groupid = 1000;
    }

    public function exchangeArray($data)
    {
        foreach($data as $key => $val)
        {
            $this->$key = (empty($val) ? $this->{$key}: $val);
        }
    }

    public function reformParam()
    {
    	$this->password = md5($this->username.':'.'My Web Site'.':'.$this->password);
    }

    public function getArrayCopy()
    {
        $objectArray = get_object_vars($this);
        foreach($objectArray as $key => $val)
        {
        	if (Utils::startsWith($key, '_'))
        	   unset($objectArray[$key]);
        }
        \Zend\Debug\Debug::dump($objectArray);
        return $objectArray;
    }

	public function getInputFilter()
	{
		if (! $this->_inputFilter)
		{
			$inputFilter = new InputFilter();
			$inputFilter->add(array(
			        'name' => 'userid',
			        'required' => false,
			        'filters' => array(
			                array('name' => 'Int')),
			));
			$inputFilter->add(array(
			        'name' => 'username',
			        'required' => true,
			        'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
			        'validators' => array(
			                array('name' => 'not_empty',),
			                array(
			                    'name' => 'string_length',
			                    'options' => array(
			                        'encoding' => 'UTF-8',
			                        'min' => 1,
			                        'max' => 10,
			                    ),
			                ),
			        ),
			    )
			);
			$inputFilter->add(array(
			        'name' => 'groupid',
			        'required' => true,
			        'filters' => array(
			                array('name' => 'Int')),
			));
			$inputFilter->add(array(
			        'name' => 'password',
			        'required' => false,
			        'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
			        'validators' => array(
			                array('name' => 'not_empty',),
			                array(
			                    'name' => 'string_length',
			                    'options' => array(
			                        'encoding' => 'UTF-8',
			                        'min' => 1,
			                        'max' => 10,
			                    ),
			                ),
			        ),
			));
			$this->_inputFilter = $inputFilter;
		}
		return $this->_inputFilter;
	}

	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Wrong usage");
	}
}