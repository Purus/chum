<?php

namespace Chum\Core;

use Symfony\Component\Validator\Constraint;

class ValidDirectory extends Constraint
{
    public string $message = 'The directory "{{ string }}" does not exists';

    public function __construct()
    {
        parent::__construct();
    }

    public function validatedBy()
    {
        return static::class . 'Validator';
    }
}