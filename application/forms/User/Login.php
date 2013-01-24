<?php

class Application_Form_User_Login extends Zend_Form
{

    public function init() {
        $this->setMethod('post')->setAttrib('id','login');
        
        $this->addElement('text', 'username', array('label'=> 'Username', 'maxlength'=> 20, 'required'=> true,
            'filters'   => array('StringTrim','StringToLower'),
            'validators'=> array(
                array('Alnum', false),
                array('StringLength', false, array(5,20))
            )
        ));
        
        $this->addElement('password', 'password', array('label'=> 'Password', 'maxlength'=> 20, 'required'=> true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(5, 20))
            )
        ));
        
        $this->addElement('hash', 'csrf', array('ignore' => true));
        
        $this->addElement('submit', 'submit', array('ignore'=> true, 'label'=> 'Login', 'class'=> 'awesome big'));
    }

}

