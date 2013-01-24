<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $identity = Zend_Auth::getInstance()->getIdentity();
            if ($identity !== null) {
                $user = Application_Model_User::find($identity->id);    
            }

            $this->view->greeting = 'Hello '.$user->getName().' !';    
        } else {
            $this->view->greeting = 'Hello guest';
        }
    
        var_dump($_SERVER);   
    
    
    }


}