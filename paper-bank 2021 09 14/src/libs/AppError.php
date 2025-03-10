<?php

use src\libs\ErrorInterface;

class AppError extends Exception implements ErrorInterface{

    protected $redirectRoute = null;

    public function __construct($redirectRoute ,$message = "", $code = 0, Throwable $previous = null)
    {
        $this->redirectRoute = $redirectRoute;
        parent::__construct($message, $code, $previous);
    }

    public function getError()
    {
        $error = [
            'type' => 'error',
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ];

        if ($_ENV['ENV'] == "development"){
            $error['error'] = $this;
        }

        return $error;
    }

    public function getRedirectRoute()
    {
        return $this->redirectRoute ?? false;
    }
}
