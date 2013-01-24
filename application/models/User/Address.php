<?php

class Application_Model_User_Address extends Application_Model_Base {
    
    const TYPE_GENERAL = 0;
    const TYPE_LEGAL = 1;
    const TYPE_OFFICE = 2;
    const TYPE_SHIPPING = 3;
    const TYPE_BILLING = 4;
    
    public static $types = array('General','Legal','Office','Shipping','Billing');
    
    /*------------------- 
     * User details info 
     *-------------------*/
    protected $id;
    protected $row_id;
    protected $row_type = 1;  // 1 = user, 2 = client
    protected $label; // 0 = general, 1 = legal, 2 = office, 3 = shipping, 4 = billing 
    protected $address1;
    protected $address2;
    protected $city;
    protected $county;
    protected $postcode;
    protected $country;
    protected $note;
    protected $created;
        
    public function __construct(array $options = null)
    {
        if (is_array($options))
            $this->setOptions($options);
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
    
    public function belongs($user)
    {
        if ($this->row_id === $user) return true;
        else return false;
    }
    
    /*------------------ 
     * Finder methods 
     *------------------*/
        
    public static function find($id)
    {
        $sql = self::getDb()->select()->from('address')->where('row_type = 1')->where('id = ?', $id);
        $row = self::getDb()->fetchRow($sql);
        if (!$row or count($row) == 0)
            return;
        // $user = new get_called_class()($row);  PHP >=5.3
        $userAddr = new self($row);
        return $userAddr;
    }
    
    public static function findByUser($user)
    {
        $sql = self::getDb()->select()->from('address')->where('row_type = 1')->where('row_id = ?', $user);
        $results = self::getDb()->fetchAll($sql);
        if (!$results) return false;
        $entries = array();
        foreach ($results as $row) {
            $entry = new self($row);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    public static function findByCity($city) 
    {
        $sql = self::getDb()->select()->from('address')->where('row_type = 1')->where('city = ?', $city);
        $row = self::getDb()->fetchRow($sql);
        if (!$row or count($row) == 0) return false;
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
            'label'        => $this->label,
            'address1'     => $this->address1,
            'address2'     => $this->address2,
            'city'         => $this->city,
            'county'       => $this->county,
            'postcode'     => $this->postcode,
            'country'      => $this->country,
            'note'         => $this->note,        
            'created'      => $created
        );
        
        if ($this->id === null) {
            unset($data['id']);
            $this->getDb()->insert('address', $data);
        } else {
            $this->getDb()->update('address', $data, array('id = ?' => $this->id, 'row_type = 1'));
        }
    }
    
    public function delete()
    {
        $this->getDb()->delete('address', array('id = ?' => $this->id, 'row_type = 1'));
        
    }
       


}

