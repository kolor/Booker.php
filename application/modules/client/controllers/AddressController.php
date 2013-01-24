<?php

class Client_AddressController extends Zend_Controller_Action
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
        $form = new Application_Form_Client_Address();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['row_id'] = $data->client;
                $address = new Application_Model_Client_Address($data);
                $address->save();
                $this->_helper->redirector('edit', array('id'=>$data->client));
            } else {
                // invalid form
            }
        }
        $this->view->client = $this->_request->getParam('client');
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