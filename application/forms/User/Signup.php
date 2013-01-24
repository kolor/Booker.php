<?php

class Application_Form_User_Signup extends Zend_Form
{

    public function init() {
        $this->setMethod('post')->setAttrib('autocomplete','off')->setAttrib('id','signup');
        
        $this->addElement('text', 'username', array('label'=> 'Username', 'maxlength'=> 20, 'required'=> true,
            'filters'   => array('StringTrim','StringToLower'),
            'validators'=> array(
                array('Alnum', false),
                array('StringLength', false, array(5,20)),
                array('Db_NoRecordExists', false, array(
                    'table' => 'user',
                    'field' => 'username',
                    'messages' => array(
                        Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => 'Username is already taken'
                    )
                ))
            )
        ));
        
        $this->addElement('password', 'password', array('label'=> 'Password', 'maxlength'=> 20, 'required'=> true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(5, 20))
            )
        ));
        
        $this->addElement('text', 'email', array('label'=> 'E-mail', 'required'=> true,
            'validators'=> array(
                array('EmailAddress', false, array('domain' => false)),
                array('Db_NoRecordExists', false, array(
                    'table' => 'user',
                    'field' => 'email',
                    'messages' => array(
                        Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => 'E-mail already in use'
                    )
                ))
            )
        ));
        
        $this->addElement('text', 'fullname', array('label'=> 'Full name', 'required'=> true,
            'validators'=> array(
                array('Alpha', false, array('allowWhiteSpace' => true))
            )
        ));
        
        $recaptcha = new Zend_Service_ReCaptcha('6LfK0cQSAAAAAMdT5hf-3q7Pqug-mTCPPe8H4RMa', '6LfK0cQSAAAAAKXX_A5Ica9a9KR9H4rpq-zQV3rt');
        
        $this->addElement('captcha', 'recaptcha', array('label'=> 'Enter CAPTCHA below:', 'required'  => true,
            'captcha'   => array(
                'captcha' => 'ReCaptcha',
				'theme'	  => 'white',
                'service' => $recaptcha
            )
        ));
        
        $this->addElement('hash', 'csrf', array('ignore'=> true));
        
        $this->addElement('submit', 'submit', array('ignore'=> true, 'label'=> 'Register', 'class'=> 'awesome big red'));
    }

}

