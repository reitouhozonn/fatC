<?php

namespace App\Repository;

use App\Models\User;

interface AuthenticatedUser
{
    public function user(): User;

    public function id(): int;
}
