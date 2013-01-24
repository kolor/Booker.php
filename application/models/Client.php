<?php

class Application_Model_Client extends Application_Model_Base {
   
    /*--------------- 
     * Client account 
     *---------------*/
    protected $id;
    protected $owner;
    protected $limit;
    protected $term;
    protected $created;
    
    /*--------------- 
     * Client details 
     *---------------*/
    protected $row_id;
    protected $row_type = 2;  // 1 = user, 2 = client
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
         
    
    protected $clientData = array('owner','limit','term','created');
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
        return $this->fname.' '.$this->lname;    
    }
    
    public function getData() 
    {
        return get_object_vars($this);
    }
    
    public function getAddress()
    {
        $addrs = Application_Model_Client_Address::findByUser($this->id);
        $ret = $addrs[0];
        return $ret;
    }

    public function getAddressString()
    {
        $addr = $this->getAddress();
        if (!$addr) return "No address";
        return $addr->address1.', '.$addr->city.', '.$addr->postcode;
    }

    public function getAddresses()
    {
        $addrs = Application_Model_Client_Address::findByClient($this->id);
        return $addrs;
    }

     /*------------------ 
     * Finder methods 
     *------------------*/   

        
    public static function find($id)
    {
        $sql = self::getDb()->select()->from(array('c' => 'client'))->join(array('d' => 'details'), 'c.id = d.row_id')
                            ->where('d.row_type = 2')->where('c.id = ?', $id);
        $row = self::getDb()->fetchRow($sql);
        if (!$row or count($row) == 0) return;
        $client = new self($row);
        $client->id = $id;
        return $client;
    }

    
    public static function findAll()
    {
        $sql = self::getDb()->select()->from(array('c' => 'client'))->join(array('d' => 'details'), 'c.id = d.row_id')
                            ->where('d.row_type = 2');
        $results = self::getDb()->fetchAll($sql);
        if (!$results) return false;
        $entries = array();
        foreach ($results as $row) {
            $entry = new self($row);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    public static function findByOwner($owner) 
    {
        $sql = self::getDb()->select()->from(array('c' => 'client'))->join(array('d' => 'details'), 'c.id = d.row_id')
                            ->where('d.row_type = 2')->where('c.owner = ?', $owner);
        $results = self::getDb()->fetchAll($sql);
        if (!$results) return false;
        $entries = array();
        foreach ($results as $row) {
            $entry = new self($row);
            $entries[] = $entry;
        }
        return $entries;
    }

    /*------------------ 
     * Database methods 
     *------------------*/

    public function save()
    {
        $data = $this->getData();
        $data['created'] = date('Y-m-d H:i:s');
        
        $clientData = array_intersect_key($data, array_flip($this->clientData)); 
        $detailsData = array_intersect_key($data, array_flip($this->detailsData)); 
        if ($this->id === null) {
        	$this->getDb()->insert('client', $clientData);
        	$detailsData['row_id'] = $this->getDb()->lastInsertId();
        	$details = new Application_Model_Client_Details($detailsData);
        } else {
        	$this->getDb()->update('client', $clientData, array('id = ?' => $this->id));
        	$details = Application_Model_Client_Details::find($this->id);
        	$details->setOptions($detailsData);
        }
        $details->save();
    }
   
   
}

