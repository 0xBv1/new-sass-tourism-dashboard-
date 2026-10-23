<?php

namespace App\Exceptions\Whatsapp;

use Exception;

class MissingConfigurationException extends Exception
{
    public function __construct(string $property)
    {
        parent::__construct("Whatsapp api missing configuration [$property]");
    }
}
