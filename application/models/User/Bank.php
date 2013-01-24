<?php

class Application_Model_User_Bank extends Application_Model_Base {
    
    const TYPE_GENERAL = 0;
    const TYPE_CARD = 1;
    const TYPE_CHEQUE = 2;
    const TYPE_PAYPAL = 3;
    const TYPE_OTHER = 4;
    
    public static $types = array('Bank','Card','Cheque','PayPal','Other');
    
    /*------------------- 
     * User details info 
     *-------------------*/
    protected $id;
    protected $row_id;
    protected $row_type = 1;  // 1 = user, 2 = client
    protected $source; // 0 = general, 1 = legal, 2 = office, 3 = shipping, 4 = billing 
    protected $bank;
    protected $swift;
    protected $account_no;
    protected $sort_code;
    protected $iban;
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
     * Database methods 
     *------------------*/
        
    public static function find($id)
    {
        $sql = self::getDb()->select()->from('bank')->where('id = ?', $id);
        $row = self::getDb()->fetchRow($sql);
        if (!$row or count($row) == 0)
            return;
        // $user = new get_called_class()($row);  PHP >=5.3
        $userBank = new self($row);
        return $userBank;
    }
    
    public static function findUser($user)
    {
        $sql = self::getDb()->select()->from('bank')->where('row_id = ?', $user)->where('row_type = 1');
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
        
    public function save()
    {
        $created = date('Y-m-d H:i:s');
        $data = array(
            'id'            => $this->id,
            'row_id'        => $this->row_id,
            'row_type'      => $this->row_type,
            'source'        => $this->source,
            'bank'          => $this->bank,
            'swift'         => $this->swift,
            'account_no'    => $this->account_no,
            'sort_code'     => $this->sort_code,
            'iban'          => $this->iban,
            'note'          => $this->note,        
            'created'       => $created
        );
        
        if ($this->id === null) {
            unset($data['id']);
            $this->getDb()->insert('bank', $data);
        } else {
            $this->getDb()->update('bank', $data, array('id = ?' => $this->id));
        }
    }
    
    public function delete()
    {
        $this->getDb()->delete('bank', array('id = ?' => $this->id));
        
    }
       


}

