<?php

namespace src\libs;

interface ErrorInterface {
    public function getError();
    public function getRedirectRoute();
}
