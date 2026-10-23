<?php

namespace App\Exceptions\Whatsapp;

use Exception;

class MissingTemplateNameException extends Exception
{
    public function __construct()
    {
        parent::__construct("Can't send whatsapp message [Missing Template Name]");
    }
}
