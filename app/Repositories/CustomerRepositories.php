<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepositories extends BaseRepository
{
    public function __construct(Customer $customer)
    {
        parent::__construct($customer);
    }
}