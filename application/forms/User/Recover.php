<?php

class Application_Form_User_Recover extends Zend_Form
{

    public function init() {
        $this->setMethod('post')->setAttrib('id','recover');
        
        $this->addElement('text', 'email', array('label'=> 'Your e-mail', 'maxlength'=> 60, 'required'=> true,
            'filters'   => array('StringTrim','StringToLower'),
            'validators'=> array(
               array('EmailAddress', false, array('domain' => false))
            )
        ));
            
        $this->addElement('hash', 'csrf', array('ignore'=> true));
        
        $this->addElement('submit', 'submit', array('ignore'=> true, 'label'=> 'Recover', 'class'=> 'awesome big'));
    }

}

