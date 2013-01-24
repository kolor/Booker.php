<?php

class ClientController extends Zend_Controller_Action
{
    protected $_flash = null;
    private $owner = 0;
    
    public function init()
    {
        /* Initialize action controller here */
        $this->_flash = $this->_helper->getHelper('FlashMessenger');
        if (!Zend_Auth::getInstance()->hasIdentity() 
          && !in_array($this->_request->getActionName(), array('nologin','success'))) {
            $this->_helper->redirector('login','user');
        }
        $this->owner = Zend_Auth::getInstance()->getIdentity()->id;
    }

    public function indexAction()
    {
        $clients = Application_Model_Client::fetchByOwner($this->owner);
        $this->view->clients = $clients;
    }
    
    public function successAction()
    {
        $this->view->messages = $this->_flash->getMessages();
    }
    
    public function addAction()
    {
        $form = new Application_Form_Client_Details();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                // add new client
                $data = $form->getValues();
                $data['owner'] = $this->owner;
                $client = new Application_Model_Client($data);
                $client->save();
                $this->_helper->redirector('index','client');
            } else {
                // invalid form
            }
        }
        $this->view->form = $form;        
    }
    
    public function addAddressAction()
    {
        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $form = new Application_Form_Client_Address();
        $id = $this->_request->getParam('id');
        if (!is_null($id)) {
            $address = Application_Model_Client_Address::find($id);
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
                    $address = new Application_Model_Client_Address($data);
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
    
}