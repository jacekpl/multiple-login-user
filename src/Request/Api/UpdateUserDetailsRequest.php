<?php

namespace App\Request\Api;

final class UpdateUserDetailsRequest
{
    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
}
