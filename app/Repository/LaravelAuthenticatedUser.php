<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

final class LaravelAuthenticatedUser  implements AuthenticatedUser
{
    public function __construct(Request $request)
    {
    }
    /**
     * Undocumented function
     *
     * @return User
     */
    public function user(): User
    {
        $user = $this->request->user();

        if ($user === null) {
            throw new AuthorizationException("通行止めです");
        }

        return $user;
    }
    /**
     * Undocumented function
     *
     * @return integer
     */
    public function id(): int
    {
        $id = $this->request->user()->id;

        if ($id === null) {
            throw new AuthorizationException('通行止めです');
        }

        return $id;
    }
}
