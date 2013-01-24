<?php

class User_AddressController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $list = Application_Model_User_Address::findByUser($userId);
        $this->view->addresses = $list;
    }

    public function addAction()
    {
        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $form = new Application_Form_User_Address();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $address = new Application_Model_User_Address($data);
                $address->setUser($userId);
                $address->save();  
                $this->view->message = 'Address - saved'; 
            } 
        }
        $this->view->form = $form;
    }


    public function editAction()
    {
        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $form = new Application_Form_User_Address();
        $id = $this->_request->getParam('id');
        if (!is_null($id)) {
            $address = Application_Model_User_Address::find($id);
            if (!$address || !$address->belongs($userId)) {
                throw new Exception('Not allowed');
            }
        }
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $address->setOptions($data);
                $address->save();  
                $this->view->message = 'Address - saved'; 
            } 
        }
        $form->populate($address->getData());
        $this->view->form = $form;
    }
    
    public function removeAction()
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
    


}

