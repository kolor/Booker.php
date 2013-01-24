<?php

class Application_Model_User extends Application_Model_Base {
   
    /*-------------- 
     * User account 
     *--------------*/
    protected $id;
    protected $username;
    protected $password;
    protected $email;
    protected $role;
    protected $level;
    protected $fullname;
    protected $code;
    protected $created;
    
    /*-------------- 
     * User details 
     *--------------*/
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
         
    
    protected $userData = array('username','password','email','role','level','fullname','code','created');
    protected $detailsData = array('row_type','fname','lname','organization','vat','telephone','mobile',
                                    'email_work','email_home','fax','skype','notes','created');
            
    
    public function __construct(array $options = null)
    {
        if (is_array($options))
            $this->setOptions($options);
        if ($this->row_id) {
            $this->id = $this->row_id;
        }
    }
    
    /*-------------------
     * Getters & setters
     *-------------------*/
    
    public function getName()
    {
        return $this->fullname;    
    }
    
    public function getData() 
    {
        return get_object_vars($this);
    }
    
    public function setPassword($pwd) 
    {
        $this->password = md5($pwd);
        return $this;
    }    

    public function setRole($level, $role) 
    {
        $this->role = $role;
        $this->level = $level;
        return $this;
    }
    
    /*------------------ 
     * Database methods 
     *------------------*/
        
    public static function find($id)
    {
        $sql = self::getDb()->select()->from(array('u' => 'user'))->join(array('d' => 'details'), 'u.id = d.row_id')
                            ->where('d.row_type = 1')->where('u.id = ?', $id);
        $row = self::getDb()->fetchRow($sql);
        if (!$row or count($row) == 0)
            return;
        $user = new self($row);
        return $user;
    }
    
    public function save()
    {
        $data = $this->getData();
        $data['created'] = date('Y-m-d H:i:s');
        
        $userData = array_intersect_key($data, array_flip($this->userData)); 
        $detailsData = array_intersect_key($data, array_flip($this->detailsData));
        if ($this->id === null) {
            $this->getDb()->insert('user', $userData);
            $detailsData['row_id'] = $this->getDb()->lastInsertId();
            $details = new Application_Model_User_Details($detailsData);
        } else {
            $this->getDb()->update('user', $userData, array('id = ?' => $this->id));
            $details = Application_Model_User_Details::find($this->id);
            $details->setOptions($detailsData);
        }
        $details->save();
    }
    
    public static function fetchAll()
    {
        $sql = self::getDb()->select()->from(array('u' => 'user'))->join(array('d' => 'details'), 'u.id = d.row_id')
                            ->where('d.row_type = 1');
        $results = self::getDb()->fetchAll($sql);
        if (!$results)
            return false;
        $entries = array();
        foreach ($results as $row) {
            $entry = new self($row);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    public static function findUsername($username) 
    {
        $sql = self::getDb()->select()->from(array('u' => 'user'))->join(array('d' => 'details'), 'u.id = d.row_id')
                            ->where('d.row_type = 1')->where('username = ?', $username);
        $row = self::getDb()->fetchRow($sql);
        if (!$row or count($row) == 0)
            return false;
        $user = new self($row);
        return $user;
    }
    
    public static function findEmail($email) 
    {
        $sql = self::getDb()->select()->from(array('u' => 'user'))->join(array('d' => 'details'), 'u.id = d.row_id')
                            ->where('d.row_type = 1')->where('email = ?', $email);
        $row = self::getDb()->fetchRow($sql);
        if (!$row or count($row) == 0)
            return false;
        $user = new self($row);
        return $user;
    }
   
}

