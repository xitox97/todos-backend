<?php

namespace App\Services\Utils;

use Exception;

class ApiException extends Exception 
{
    //array of id, type
    protected $data = array();

    public function __construct($message = null, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function addData($type, $id) 
    {
        $this->data[] = array('type'=>$type, 'id'=>$id);
    }

    public function getData() 
    {
        return $this->data;
    }
}