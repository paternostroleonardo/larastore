<?php

namespace App\Repositories;

use App\Models\User;

class SellerRepositories extends BaseRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}