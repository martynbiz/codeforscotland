<?php 
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

use Users\Form\RegisterForm;
use Users\Form\RegisterFilter;

use Users\Form\LoginForm;
use Users\Form\LoginFilter;

use Users\Model\User;


class UsersController extends AbstractActionController
{
  
  protected $userTable;
  protected $authService;
  
  public function indexAction()
  {
      return new ViewModel();
  }
  
  public function registerAction()
  {
    $form = new RegisterForm();
    
    $form->get('submit')->setValue('Add');
    
    $request = $this->getRequest();
    if ($request->isPost()) {
      $user = new User();
      $form->setInputFilter(new RegisterFilter()); // dependency injection
      $form->setData($request->getPost());

      if ($form->isValid()) {
        $user->exchangeArray($form->getData());
        $this->getUserTable()->saveUser($user);
        
        return $this->redirect()->toUrl('/');
      }
    }
    
    return array('form' => $form);
  }
  
  public function loginAction()
  {
    $form = new LoginForm();
    
    $form->get('submit')->setValue('Login');
    
    $request = $this->getRequest();
    if ($request->isPost()) {
        
        $this->getAuthService()
          ->getAdapter()
          ->setIdentity($this->request->getPost('email'))
          ->setCredential($this->request->getPost('password'));
        
        $result = $this->getAuthService()->authenticate();
        
        if ($result->isValid()) {
          $this->getAuthService()->getStorage()->write($this->request->getPost('email'));
          //\Zend\Debug\Debug::dump();
          return $this->redirect()->toUrl('/');
        }
    }
    
    return new ViewModel(array(
      'form' => $form,
    ));
  }
  
  
  
  
  protected function getAuthService() {
    if (! $this->authService) {
      $dbAdapter = $this->getServiceLocator()->get('\Zend\Db\Adapter\Adapter');
      
      $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'users', 'email', 'password', 'MD5(?)');
      
      $authService = new AuthenticationService();
      $authService->setAdapter($dbTableAuthAdapter);
      
      $this->authService = $authService;
    }
    
    return $this->authService;
  }
  
  protected function getUserTable()
  {
    if (!$this->userTable) {
      $sm = $this->getServiceLocator();
      $this->userTable = $sm->get('User\Model\UserTable');
    }
    return $this->userTable;
  }
  
}

