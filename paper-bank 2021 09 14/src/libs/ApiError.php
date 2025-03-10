<?php

use src\libs\ErrorInterface;

class ApiError extends Exception implements ErrorInterface {

    protected $redirectRoute = null;

    public function getError()
    {
        $status = 'success';

        // Client Errors
        if ($this->getCode() >= 400 && $this->getCode() < 500)
            $status = "client-error";

        // Server Side Errors
        if ($this->getCode() >= 500 && $this->getCode() < 600)
            $status = "server-error";

        $error = [
            'status' => $status,
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ];

        if (ENV == "development"){
            $error['error'] = $this;
        }

        return $error;
    }

    public function getRedirectRoute()
    {
        return $this->redirectRoute ?? false;
    }
}
