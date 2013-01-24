<?php

class Application_Model_Item extends Application_Model_Base {
    
    /*-----------------
     * Item properties
     *-----------------*/
    protected $id;
    protected $sku;
    protected $barcode;
    protected $title;
    protected $descr;
    protected $measure;
    protected $price;
    protected $currency;
    protected $tax;
    
    public function __construct(array $options = null)
    {
        if (is_array($options))
            $this->setOptions($options);
    }

    
    /*-------------------
     * Getters & setters
     *-------------------*/

    
    /*------------------
     * Database methods
     *------------------*/

    public static function find($id)
    {
        $sql = self::getDb()->select()->from('item')->where('id = ?', $id);
        $row = self::getDb()->fetchRow($sql);
        if (!$row or count($row) == 0)
            return;
        $user = new self($row);
        return $user;
    }

    public function save()
    {
        $data = array(
            'id'        => $this->id,
            'sku'       => $this->sku,
            'barcode'   => $this->barcode,
            'title'     => $this->title,
            'descr'     => $this->descr,
            'measure'   => $this->measure,
            'price'     => $this->price,
            'currency'  => $this->currency,
            'tax'       => $this->tax
        );
        
        if ($this->id === null) {
            unset($data['id']);
            $this->getDb()->insert('item', $data);
        } else {
            $this->getDb()->update('item', $data, array('id = ?' => $this->id));
        }
    }
   
    public static function fetchAll()
    {
        $sql = self::getDb()->select()->from('item');
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
    


    public static function findSKU($sku) 
    {
        $sql = self::getDb()->select()->from('item')->where('SKU = ?', $sku);
        $row = self::getDb()->fetchRow($sql);
        if (!$row or count($row) == 0)
            return;
        $user = new self($row);
        return $user;
    }

   
}

