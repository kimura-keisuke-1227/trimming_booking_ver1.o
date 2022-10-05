<?php

namespace App\Repositories\Eloquents;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
##下記のuseを追加##
use Illuminate\Support\Facades\Hash;



class UserRepository implements UserRepositoryInterface
{
    private $user;

    /**
     * constructor
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function findFromEmail(string $email): User
    {
        return $this->user->where('email', $email)->firstOrFail();
    }

        ##ここから追加##
    /**
     * @inheritDoc
     */
    public function updateUserPassword(string $password, int $id): void
    {
        $this->user->where('id', $id)->update(['password' => Hash::make($password)]);
    }
}