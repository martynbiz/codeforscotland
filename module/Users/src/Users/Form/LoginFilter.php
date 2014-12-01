<?php
namespace Users\Form;

use Zend\InputFilter\InputFilter;

class LoginFilter extends InputFilter {
  
  public function __construct() {
    
    $this->add(array(
      'name' => 'email',
      'required' => true,
    ));
    
    $this->add(array(
      'name' => 'password',
      'required' => true,
    ));  
    
  }  
  
}
