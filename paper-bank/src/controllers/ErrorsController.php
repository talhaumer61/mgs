<?php

class Errors extends  Controller {
    public function __construct()
    {
        $this->authMiddleware();
    }

    public function index()
    {
        return require_once (APPROOT . "/views/errors/index.php");
    }
}