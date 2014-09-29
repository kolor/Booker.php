<?php

class UserController extends Zend_Controller_Action
{
    protected $_flash = null;
    
    public function init()
    {
        /* Initialize action controller here */
        $this->_flash = $this->_helper->getHelper('FlashMessenger');
        if (Zend_Auth::getInstance()->hasIdentity() 
          && !in_array($this->_request->getActionName(), array('register','login','recover','success'))) {
            $this->_helper->redirector('login','user');
        }
    }

    public function indexAction()
    {
        echo '<pre>';
        // user profile for default action
        $user = Application_Model_User::find('1');
        echo $user->fullname;
        
    }
    
    public function registerAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index','user');
        }
        $form = new Application_Form_User_Register();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $user = new Application_Model_User($data);
                $user->code = 0;
                $user->setPassword($data['password'])->setRole(3, 'Owner')->save();
                $msg = "Welcome to Booker!\n\nYour username is: {$data[username]}\nAnd your password is: {$data[password]}\n\nKeep it safe!";
                $this->_sendEmail($data['email'], 'Booker registration confirmation', $msg);
                $this->_login($data['username'], md5($data['password']));
                $this->_flash->addMessage("Registration complete!");
                $this->_flash->addMessage("Your account has been created and confirmation letter sent to e-mail.");
                $this->_helper->redirector('success','user');
            } else {
                // invalid form
            }
        }
        $this->view->form = $form;
    }
    
    public function loginAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index','user');
        }
        $form = new Application_Form_User_Login();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                // checking data against user table
                $result = $this->_login($form->getValue('username'), md5($form->getValue('password')));
                if ($result->isValid()) {
                    $this->_helper->redirector('index', 'index');
                } else {
                    if ($result->getCode() == Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND) {
                        $this->view->error = '<div class=error>Username not found</div>';    
                    } else if ($result->getCode() == Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID) {
                        $this->view->error = '<div class=error>Password incorrect</div>';
                    } else {
                        $this->view->error = '<div class=error>Login failed</div>';
                    } 
                }
            } else {
                // invalid form
            }
        }
        $this->view->form = $form;
    }
    
   
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index','index');
    }
    
    public function recoverAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index','user');
        }
        if ($this->_request->getParam('do') != 'reset') {             // showing form to request password
            $form = new Application_Form_User_Recover();
            if ($this->_request->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $email = $form->getValue('email');
                    $user = Application_Model_User::findEmail($email);
                    if ($user !== false) {
                        // generate unique code
                        require_once "Text/Password.php";
                        $code = Text_Password::create();
                        $user->code = $code;
                        $user->save();
                        $url = 'http://'.$_SERVER['HTTP_HOST'].'/user/recover/do/reset/user/'.$user->id.'/code/'.$code;
                        $this->_sendEmail($email, 'Booker: password reset', "Your username is {$user->username}\nTo reset password - click here: $url");
                        $this->_flash->addMessage("Password recovery - Step 1");
                        $this->_flash->addMessage("Password reset confirmation link has been sent to you by e-mail.");
                        $this->_helper->redirector('success','user');
                    } else {
                        $this->view->error = '<div class=error>E-mail not found</div>';
                    }
                }
            }
            $this->view->form = $form;
        } else {                                                  // showing confirmation of password reset
            $code = $this->_request->getParam('code');
            $userId = $this->_request->getParam('user');
            $user = Application_Model_User::find($userId);
            if ($code === $user->code) {
                require_once "Text/Password.php";
                $pwd = Text_Password::create(10);
                $user->setPassword($pwd);
                $user->code = '0';
                $user->save();
                $this->_sendEmail($user->email, 'Booker: new password', "Your password has been reset to: $pwd\nYou can change it in your profile.");                
                $this->_flash->addMessage("Password recovery - Step 2");
                $this->_flash->addMessage("Your password has been reset and sent to you by e-mail.");
                $this->_helper->redirector('success','user');
            } else {
                $this->view->error = 'Confirmation code is incorrect';
            }
        }
    }

    public function successAction()
    {
        $this->view->messages = $this->_flash->getMessages();
    }
    
    public function profileAction()
    {
        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $form = new Application_Form_User_Profile();
        $user = Application_Model_User::find($userId); 
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $user->setOptions($data);
                $user->save();
            }
        }
        $form->populate($user->getData());
        $this->view->form = $form;
    }
    
    /*--------------------
     * Address operations 
     *--------------------*/
    
	public function addressAction()
    {
        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $list = Application_Model_User_Address::findUser($userId);
        $this->view->addresses = $list;
    }
    
    public function addAddressAction()
    {
        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $form = new Application_Form_User_Address();
        $id = $this->_request->getParam('id');
        if (!is_null($id)) {
            $address = Application_Model_User_Address::find($id);
            $this->view->edit = true;
            if (!$address || !$address->belongs($userId)) {
                throw new Exception('Not allowed');
            }
        }
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                if ($address) {
                    $address->setOptions($data);
                } else {    // add new address
                    $address = new Application_Model_User_Address($data);
                    $address->setUser($userId);
                }
                $address->save();  
                $this->view->message = 'Address has been saved'; 
            } 
        }
        if (!is_null($address)) {
            $form->populate($address->getData());
        } 
        $this->view->form = $form;
    }
    
    public function delAddressAction()
    {
        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $id = $this->_request->getParam('id');
        $address = Application_Model_User_Address::find($id);
        if (!$address || !$address->belongs($userId)) {
            throw new Exception('No address found');
        } else {
            $address->delete();
        }
        $this->_redirect('/user/address');
    }
    
    /*--------------------
     * Bank operations 
     *--------------------*/
    
    public function bankAction()
    {
        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $list = Application_Model_User_Bank::findUser($userId);
        $this->view->bank = $list;
    }
    
    public function addBankAction()
    {
        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $form = new Application_Form_User_Bank();
        $id = $this->_request->getParam('id');
        if (!is_null($id)) {
            $bank = Application_Model_User_Bank::find($id);
            $this->view->edit = true;
            if (!$bank || !$bank->belongs($userId)) {
                throw new Exception('Not allowed');
            }
        }
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                if ($bank) {
                    $bank->setOptions($data);
                } else {    // add new bank
                    $bank = new Application_Model_User_Bank($data);
                    $bank->setUser($userId);
                }
                $bank->save();  
                $this->view->message = 'Bank details have been saved'; 
            } 
        }
        if (!is_null($bank)) {
            $form->populate($bank->getData());
        } 
        $this->view->form = $form;
    }
    
    public function delBankAction()
    {
        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $id = $this->_request->getParam('id');
        $bank = Application_Model_User_Bank::find($id);
        if (!$bank || !$bank->belongs($userId)) {
            throw new Exception('No bank details found');
        } else {
            $bank->delete();
        }
        $this->_redirect('/user/bank');
    }
    
    
    public function settingsAction()
    {
        
    }
    
    public function subscribeAction()
    {
        
    }
    
    public function statsAction()
    {
        
    }
	
    public function testAction()
    {
        
    }
    
    private function _login($username, $password)
    {
        $adapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('config')->get('db'), 'user' , 'username', 'password');
        $adapter->setIdentity($username);
        $adapter->setCredential($password);
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
            $auth->getStorage()->write($adapter->getResultRowObject(null,'password'));
        }
        return $result; 
    }
    
    private function _sendEmail($to, $subj, $msg) 
    {
        $mail = new Zend_Mail();
        $mail->setBodyText($msg);
        $mail->setFrom('admin@webapi.us', 'Booker');
        $mail->addTo($to, 'Booker user');
        $mail->setSubject($subj);
        $mail->send();
    }


}

