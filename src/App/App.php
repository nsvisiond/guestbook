<?php

namespace GuestBook\App;

use GuestBook\Controllers\ControllerInterface;

class App
{
    public function __construct(ControllerInterface $controller)
    {
        $dotenv = \Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '/..');
        $dotenv->load();
        $controller->run();
    }
}