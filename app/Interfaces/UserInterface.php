<?php

namespace App\Interfaces;

interface UserInterface
{
    public function findUserByEmail($email);
    
    public function createUser($request);
}
