<?php

class User_AccountController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_flash = $this->_helper->getHelper('FlashMessenger');
    }

    public function indexAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index','index');
        } else {
            $this->_helper->redirector('login');
        }
    }

    public function signupAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index','index');
        }
        $form = new Application_Form_User_Signup();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
            	$this->_signup($form->getValues());               
            } else {
                // invalid form
            }
        }
        $this->view->form = $form;
    }

    private function _signup($data)
    {
    	$user = new Application_Model_User($data);
        $user->code = 0;
        $user->setPassword($data['password'])->setRole(3, 'Owner')->save();
        $msg = "Welcome to Booker!\n\nYour username is: $data[username]\nAnd your password is: $data[password]\n\nKeep it safe!";
        $this->_sendEmail($data['email'], 'Booker registration confirmation', $msg);
        $this->_login($data['username'], md5($data['password']));
        $this->_flash->addMessage("Registration complete!");
        $this->_flash->addMessage("Your account has been created and confirmation letter sent to e-mail.");
        $this->_helper->redirector('success');
    }

    public function confirmAction()
    {

    }

    public function loginAction()
    {

        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index','index');
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
                        $url = 'http://'.$_SERVER['HTTP_HOST'].'/user/account/recover/do/reset/user/'.$user->id.'/code/'.$code;
                        $this->_sendEmail($email, 'Booker: password reset', "Your username is {$user->username}\nTo reset password - click here: $url");
                        $this->_flash->addMessage("Password recovery - Step 1");
                        $this->_flash->addMessage("Password reset confirmation link has been sent to you by e-mail.");
                        $this->_helper->redirector('success');
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
                $this->_helper->redirector('success');
            } else {
                $this->view->error = 'Confirmation code is incorrect';
            }
        }

    }

    public function successAction()
    {
    	$this->view->messages = $this->_flash->getMessages();
    }

    private function _sendEmail($to, $subj, $msg) 
    {
        $mail = new Zend_Mail();
        $mail->setBodyText($msg);
        $mail->setFrom('admin@airy.me', 'Booker');
        $mail->addTo($to, 'Booker user');
        $mail->setSubject($subj);
        $mail->send();
    }


}

