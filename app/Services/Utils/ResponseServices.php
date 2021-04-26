<?php

namespace App\Services\Utils;

use Exception;

class ResponseServices
{
    private static $instance = null;
    private $success = null;
    private $type = null;
    private $data = null;
    private $message = null;
    private $title = null;
    private $errorCode = 0;

    private $response = [];

    public static function success($message = null)
    {
        $instance = self::getInstance();

        $instance->success = true;
        $instance->type = 'success';

        if ($message !== null) {
            $instance->message = $message;
        }

        return $instance;
    }

    public static function error($message = null, $errorCode = 0)
    {
        $instance = self::getInstance();

        $instance->success = false;
        $instance->type = 'error';

        $instance->errorCode = $errorCode;
        if ($message !== null) {
            $instance->message = $message;
        }

        return $instance;
    }

    public function data($data, $key = 'data')
    {
        $this->data = $data;

        if ($this->data !== null) {
            $type = [$key => $this->data];
            $this->response = array_merge($this->response, $type);
        }
        return $this;
    }

    public function type($type)
    {
        $this->type = $type;
        if ($this->type !== null) {
            $type = ['type' => $this->type];
            $this->response = array_merge($this->response, $type);
        }
        return $this;
    }

    public function title($title)
    {
        $this->title = $title;
        if ($this->title !== null) {
            $title = ['title' => $this->title];
            $this->response = array_merge($this->response, $title);
        }
        return $this;
    }

    public function toJson()
    {
        $response = $this->prepareResponse();
        return response()->json($response);
    }

    public function toArray()
    {
        $response = $this->prepareResponse();
        return response($response);
    }

    private static function getInstance()
    {
        if (self::$instance == null) {
            return new self;
        } else {
            return self::$instance;
        }
    }

    private function prepareResponse()
    {
        $this->response = array_merge($this->response, [
            'success' => $this->success
        ]);

        if ($this->data !== null) {
            $data = ['data' => $this->data];
            $this->response = array_merge($this->response, $data);
        }

        if ($this->message !== null) {
            $message = ['message' => $this->message];
            $this->response = array_merge($this->response, $message);
        }

        if ($this->errorCode > 0) {
            $errorCode = ['error_code' => $this->errorCode];
            $this->response = array_merge($this->response, $errorCode);
        }

        return $this->response;
    }
}
