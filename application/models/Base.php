<?php

class Application_Model_Base {
      
    protected static $_db;
    
    public function __set($name, $value)
    {
        $this->$name = $value;
        return $this;
    }
    
    public function __get($name)
    {
        $method = 'get'.$name;
        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            return $this->$name;
        }
    }
    
    public function setOptions(array $options)
    {
        foreach ($options as $k => $v) {
            $this->$k = $v;
        }
        return $this;
    }
    
    public static function getDb()
    {
        if (self::$_db == null) {
            self::$_db = Zend_Db::factory(Zend_Registry::get('config')->get('resources')->get('db'));
        }
        self::$_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        return self::$_db;
    }
   
}
