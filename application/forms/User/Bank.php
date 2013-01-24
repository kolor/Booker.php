<?php

class Application_Form_User_Bank extends Zend_Form
{

    public function init() {
    
        $this->setMethod('post')->setAttrib('autocomplete','off')->setAttrib('id','banking');
        
        $select = $this->createElement('select', 'source', array('class' => 'chzn-select', 'data-placeholder' => 'Type', 'style' => 'width:320px'))->setLabel('Type')->addMultiOptions(array(
            '0' => 'Bank',
            '1' => 'Card',
            '2' => 'Cheque',
            '3' => 'PayPal',
            '4' => 'Other',
        ));
        
        $this->addElement($select);
        
		$this->addElement('text', 'bank', array('label'=>'Bank', 'maxlength'=>30, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(4,30))
            )
        ));

		$this->addElement('text', 'swift', array('label'=>'Swift', 'maxlength'=>20, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(3,20))
            )
        ));

        $this->addElement('text', 'iban', array('label'=>'Iban', 'maxlength'=>30, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(3,30))
            )
        ));
        
		$this->addElement('text', 'account_no', array('label'=>'Account no', 'maxlength'=>15, 'required'=>false,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(4,15))
            )
        ));

		$this->addElement('text', 'sort_code', array('label'=>'Sort code', 'maxlength'=>10, 'required'=>false,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(4,10))
            )
        ));
        
        $this->addElement('text', 'note', array('label'=>'Notes', 'maxlength'=>25, 'required'=>false,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(3,25))
            )
        ));
        
		$this->addElement('submit', 'submit', array('ignore'=> true, 'label'=> 'Update', 'class' => 'awesome big'));

	}
}
?>