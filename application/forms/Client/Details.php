<?php

class Application_Form_Client_Details extends Zend_Form
{

    public function init() {
        $this->setMethod('post')
             ->setAttrib('autocomplete','off')
             ->setAttrib('id','client');
        
        $this->addElement('text', 'fname', array('label'=>'First name', 'maxlength'=>20, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('Alpha', false),
                array('StringLength', false, array(2,20))
            )
        ));
        
        $this->addElement('text', 'lname', array('label'=>'Last name', 'maxlength'=>20, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('Alpha', false),
                array('StringLength', false, array(3,20))
            )
        ));
        
        $this->addElement('text', 'organization', array('label'=>'Organization', 'maxlength'=>80, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(4,80))
            )
        ));
        
        $this->addElement('text', 'vat', array('label'=>'Registration number', 'maxlength'=>11, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(4,11))
            )
        ));
        
        $this->addElement('text', 'email_work', array('label'=>'E-mail (work)','required'=>true));

        $this->addElement('text', 'email_home', array('label'=>'E-mail (home)'));
        
        $this->addElement('text', 'telephone', array('label'=>'Telephone', 'maxlength'=>14, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('Digits'),
                array('StringLength', false, array(4,14))
            )
        ));        
        
        $this->addElement('text', 'mobile', array('label'=>'Mobile', 'maxlength'=>14,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('Digits'),
                array('StringLength', false, array(4,14))
            )
        ));  
          
        $this->addElement('text', 'fax', array('label'=>'Fax', 'maxlength'=>14,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('Digits'),
                array('StringLength', false, array(4,14))
            )
        )); 
        
        $this->addElement('text', 'skype', array('label'=>'Skype',
            'validators'=> array(
                array('StringLength', false)
            )
        ));

        $this->addElement('text', 'limit', array('label'=>'Limit', 'maxlength'=>6, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('Float', false),
                array('StringLength', false, array(2,6))
            )
        ));
        
        $this->addElement('text', 'term', array('label'=>'Term', 'maxlength'=>5, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('Int', false),
                array('StringLength', false, array(2,5))
            )
        ));

        $this->addElement('textarea', 'notes', array('label' => 'Notes', 'cols'=> 44, 'rows'=> 4));
        
        $this->addElement('submit', 'submit', array('ignore'=> true, 'label'=> 'Save', 'class' => 'awesome big'));
    }

}

