<?php
namespace frontend\components;

use yii\web\HttpException;

/**
 *
 * @author Administrator
 *        
 */
class MyException extends HttpException
{
    // TODO - Insert your code here
    
    /**
     *
     * @param int $status
     *            HTTP status code, such as 404, 500, etc.
     *            
     * @param string $message
     *            error message
     *            
     * @param int $code
     *            error code
     *            
     * @param \Exception $previous
     *            The previous exception used for the exception chaining.
     *            
     */
    public function __construct($status, $message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($status, $message = null, $code = 0, $previous = null);
        // TODO - Insert your code here
    }

    /**
     */
    function __destruct()
    {
        
        // TODO - Insert your code here
    }
}

?>