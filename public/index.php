<?php

use GuestBook\App\App;
use GuestBook\Controllers\GuestBook;

require_once realpath(__DIR__ . '/../vendor/autoload.php');

$page = new App(new GuestBook());