<?php

class Application_Model_User_Details extends Application_Model_Base {
    
    /*------------------- 
     * User details info 
     *-------------------*/
    protected $id;
    protected $row_id;
    protected $row_type = 1;  // 1 = user, 2 = client
    protected $fname;
    protected $lname;
    protected $organization;
    protected $vat;
    protected $telephone;
    protected $mobile;
    protected $email_work;
    protected $email_home;
    protected $fax;
    protected $skype;
    protected $notes;
    protected $created;
        
    public function __construct(array $options = null)
    {
        if (is_array($options)) {    
            $this->setOptions($options);
            $this->id = $options['row_id'];
        }
        
    }
    
    /*-------------------
     * Getters & setters
     *-------------------*/
    public function setUser($user) 
    {
        $this->row_id = $user;
        return $this;
    }
    
    public function getData() 
    {
        return get_object_vars($this);
    }
    

    /*------------------ 
     * Finder methods 
     *------------------*/

    public static function find($user)
    {
        $sql = self::getDb()->select()->from('details')->where('row_type = 1')->where('row_id = ?', $user);
        $row = self::getDb()->fetchRow($sql);
        if (!$row or count($row) == 0) {
        	$userDetails = new self();        
        } else {
        	$userDetails = new self($row);
        }
        return $userDetails;
    }

    public static function findByVAT($vat) 
    {
        $sql = self::getDb()->select()->from('details')->where('vat = ?', $vat);
        $row = self::getDb()->fetchRow($sql);
        if (!$row or count($row) == 0)
            return false;
        $user = new self($row);
        return $user;
    }

    
    /*------------------ 
     * Database methods 
     *------------------*/
    
    public function save()
    {
        $created = date('Y-m-d H:i:s');
        $data = array(
            'id'           => $this->id,
            'row_id'       => $this->row_id,
            'row_type'     => $this->row_type,
            'fname'        => $this->fname,
            'lname'        => $this->lname,
            'organization' => $this->organization,
            'vat'          => $this->vat,
            'telephone'    => $this->telephone,
            'mobile'       => $this->mobile,
            'email_work'   => $this->email_work,
            'email_home'   => $this->email_home,
            'fax'          => $this->fax,
            'skype'        => $this->skype,
            'notes'        => $this->notes,        
            'created'      => $created
        );
        
        if ($this->id === null) {
            unset($data['id']);
            $this->getDb()->insert('details', $data);
        } else {
            $this->getDb()->update('details', $data, array('id = ?' => $this->id, 'row_type = 1'));
        }
    }
   
}

