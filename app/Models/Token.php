<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken;

class Token extends PersonalAccessToken
{
    use SerializeDate;

    protected $table = 'personal_access_tokens';
}
