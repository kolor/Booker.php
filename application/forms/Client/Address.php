<?php

class Application_Form_Client_Address extends Zend_Form
{

    public function init() {
    
        $this->setMethod('post')->setAttrib('autocomplete','off')->setAttrib('id','address');
        
        $select = $this->createElement('select', 'label', array('class' => 'chzn-select', 'data-placeholder' => 'Address type', 'style' => 'width:253px'))->setLabel('Address type')->addMultiOptions(array(
            '0' => 'General',
            '1' => 'Legal',
            '2' => 'Office',
            '3' => 'Shipping',
            '4' => 'Billing',
        ));
        
        $this->addElement($select);
        
		$this->addElement('text', 'address1', array('label'=>'Address line 1', 'maxlength'=>30, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(4,30))
            )
        ));
        
		$this->addElement('text', 'address2', array('label'=>'Address line 2', 'maxlength'=>30, 'required'=>false,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(4,30))
            )
        ));

		$this->addElement('text', 'city', array('label'=>'City', 'maxlength'=>20, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(3,20))
            )
        ));

		$this->addElement('text', 'county', array('label'=>'County', 'maxlength'=>15, 'required'=>false,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(4,15))
            )
        ));

		$this->addElement('text', 'postcode', array('label'=>'Postcode', 'maxlength'=>10, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(4,10))
            )
        ));

		$this->addElement('text', 'country', array('label'=>'Country', 'maxlength'=>30, 'required'=>true,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(3,30))
            )
        ));
        
        $this->addElement('text', 'note', array('label'=>'Notes', 'maxlength'=>25, 'required'=>false,
            'filters'   => array('StringTrim'),
            'validators'=> array(
                array('StringLength', false, array(3,30))
            )
        ));
        
		$this->addElement('submit', 'submit', array('ignore'=> true, 'label'=> 'Save', 'class' => 'awesome big'));

	}
}
?>