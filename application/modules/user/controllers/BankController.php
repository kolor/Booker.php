<?php

class User_BankController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $list = Application_Model_User_Bank::findUser($userId);
        $this->view->bank = $list;
    }

    public function addAction()
    {
    	$userId = Zend_Auth::getInstance()->getIdentity()->id;
        $form = new Application_Form_User_Bank();
        
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $bank = new Application_Model_User_Bank($data);
                $bank->setUser($userId);
                $bank->save();  
                $this->view->message = 'Bank details - saved'; 
            } 
        }
        $this->view->form = $form;
    }


    public function removeAction()
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

    public function editAction()
    {
    	$userId = Zend_Auth::getInstance()->getIdentity()->id;
        $form = new Application_Form_User_Bank();
        
    	$id = $this->_request->getParam('id');
        if (!is_null($id)) {
            $bank = Application_Model_User_Bank::find($id);
            if (!$bank || !$bank->belongs($userId)) {
                throw new Exception('Not allowed');
            }
        }	
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $bank->setOptions($data);
                $bank->save();  
                $this->view->message = 'Bank details - saved'; 
            } 
        }
        $form->populate($bank->getData());
        $this->view->form = $form;
    }


}

