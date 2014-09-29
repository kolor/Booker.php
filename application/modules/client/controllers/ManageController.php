<?php

class Client_ManageController extends Zend_Controller_Action
{
    protected $_flash = null;
    private $owner = 0;
    
    public function init()
    {
        /* Initialize action controller here */
        $this->_flash = $this->_helper->getHelper('FlashMessenger');
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('login','account','user');
        }
        $this->owner = Zend_Auth::getInstance()->getIdentity()->id;
    }

    public function indexAction()
    {
        $clients = Application_Model_Client::findByOwner($this->owner);
        $this->view->clients = $clients;
    }

    public function addAction()
    {
        $form = new Application_Form_Client_Details();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['owner'] = $this->owner;
                $data['email_home'] = $data['email_work'];
                $client = new Application_Model_Client($data);
                $client->save();
                $this->_helper->redirector('index');
            } else {
                // invalid form
            }
        }
        $this->view->form = $form;        
    }

    public function editAction()
    {
        $id = $this->_request->getParam('id');
        $form = new Application_Form_Client_Details();
        $client = Application_Model_Client::find($id);
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $client->setOptions($data);
                $client->save();
            } else {
                // invalid form
            }
        }     
        $form->populate($client->getData());
        $this->view->form = $form;
        $this->view->address = $client->getAddresses();

    }

    public function successAction()
    {
        $this->view->messages = $this->_flash->getMessages();
    }

}