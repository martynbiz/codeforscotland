<?php
namespace Users\Form;

use Zend\Form\Form;

class LoginForm extends Form {
  
  public function __construct() {
    
    parent::__construct('Login');
    
    $this->setAttribute('method', 'post');
    $this->setAttribute('enctype', 'multipart/form-data');
    
    $this->add(array(
			'name' => 'email',
			'attributes' => array(
				'type' => 'text',
				'required' => 'required',
			),
			'options' => array(
				'label' => 'Email',
			),
			'filters' => array(
				array(
					'name' => 'StringTrim',
				),
			),
			'validators' => array(
        array(
	        'name' => 'EmailAddress',
	        'options' => array(
		        'messages' => array(
			        \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Email address format is invalid'
		        ),
	        ),
        ),
			),
		));
		
		$this->add(array(
			'name' => 'password',
			'attributes' => array(
				'type' => 'password',
				'required' => 'required',
			),
			'options' => array(
				'label' => 'Password',
			),
		));

    $this->add(array(
        'name' => 'submit',
        'attributes' => array(
            'type'  => 'submit',
            'value' => 'Register',
            'id' => 'submitbutton',
        ),
    ));
    
    
  }
  
}
