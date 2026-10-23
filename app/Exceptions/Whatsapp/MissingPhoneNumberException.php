<?php

namespace App\Exceptions\Whatsapp;

use Exception;

class MissingPhoneNumberException extends Exception
{
    public function __construct()
    {
        parent::__construct("Can't send whatsapp message [Missing Phone Number]");
    }
}
