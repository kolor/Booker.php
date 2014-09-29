<?php

class User_ProfileController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
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
        if ($user) {
            $form->populate($user->getData());    
        }
        $this->view->form = $form;
    }


}

