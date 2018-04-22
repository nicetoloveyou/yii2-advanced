<?php
namespace components;

use yii\base\Object;

/**
 *
 * @author Administrator
 *        
 */
class Car extends Object
{
    protected $card;
    
    private $price;
    
    public function __construct($card, $price){
        $this->card = $card;
        $this->price = $price;
    }
    /**
     * 
     * 
     * @see \yii\base\Object::__isset()
     */
    public function __isset($name){
        return isset($this->$name);
    }
    
    public function getCard(){
        return $this->card;
    }
    
    public function setCard($card){
        $this->card = $card;
    }
       
    
    // TODO - Insert your code here
}

?>