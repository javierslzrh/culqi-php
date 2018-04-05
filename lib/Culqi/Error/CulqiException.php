<?php 

namespace Culqi\Error;

/**
 * Base Culqi Exception
 */
abstract class CulqiException extends \Exception {
    protected $merchant_message = "";

    public static function fromJson($jsonString) {
    	$exceptionObject = null;
    	$jsonObject = json_decode($jsonString);
    	if ($jsonObject instanceof \STDClass){
    		if($jsonObject->object == 'error'){
    			$exceptionObject = new static();
    			
    			if(!empty($jsonObject->merchant_message)){
    				$exceptionObject->setMerchantMessage($jsonObject->merchant_message);
    			}


    			if(!empty($jsonObject->user_message)){
    				$exceptionObject->setMessage($jsonObject->user_message);	
    			}

    		}

    	}

    	return $exceptionObject;
    }

    public function setMessage($message){
        $this->message = $message;
    }

    public function setMerchantMessage($merchant_message){
    	$this->merchant_message = $merchant_message;
    }

    public function getMerchantMessage(){
    	return $this->merchant_message;
    }

}