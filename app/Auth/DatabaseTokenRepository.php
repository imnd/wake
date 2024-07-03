<?php

namespace App\Auth;

use Illuminate\Auth\Passwords\DatabaseTokenRepository as BaseDatabaseTokenRepository;

class DatabaseTokenRepository extends BaseDatabaseTokenRepository
{
    public function createNewToken()
    {
        return substr(parent::createNewToken(), 0, 6);
    }
}
