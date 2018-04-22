<?php
namespace components;

/**
 *
 * @author Administrator
 *
 * 工具组件
 * (tips: 自定义组件最好继承基类Object)
 */

class Tools
{
    // TODO - Insert your code here
    
    public $version;
    
    private $author;
    
    /**
     */
    function __construct($config = [], $version, $author)
    {
        //parent::__construct($config);
        // TODO - Insert your code here
        $this->version = $version;
        $this->author = $author;
    }
    
    public function __isset($name)
    {
        //var_dump($this->author);
        return isset($this->$name);
    }
    
//     public function __get($name)
//     {
//         return $this->author;
//     }
    
//     public function getAuthor()
//     {
//         return $this->author;
//     }
    
    /**
     * 
     * @return string
     */
    public function executeCrond()
    {
        return 'executeCrond';
    }
    
    /**
     */
    function __destruct()
    {
        
        // TODO - Insert your code here
    }
}

?>