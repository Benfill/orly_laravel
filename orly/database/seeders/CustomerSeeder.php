<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Create customers for non-staff users (user_id 3, 4, 5, 6)
        $customers = [
            [
                'user_id' => 3,
                'phone' => '+1-555-0101',
                'address' => '123 Main St, New York, NY 10001',
            ],
            [
                'user_id' => 4,
                'phone' => '+1-555-0102',
                'address' => '456 Oak Ave, Los Angeles, CA 90001',
            ],
            [
                'user_id' => 5,
                'phone' => '+1-555-0103',
                'address' => '789 Pine Rd, Chicago, IL 60601',
            ],
            [
                'user_id' => 6,
                'phone' => '+1-555-0104',
                'address' => '321 Elm St, Houston, TX 77001',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
