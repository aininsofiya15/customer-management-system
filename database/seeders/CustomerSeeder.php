<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Insert 100 fake customers using the CustomerFactory.
     */
    public function run(): void
    {
        Customer::factory()->count(100)->create();
    }
}
