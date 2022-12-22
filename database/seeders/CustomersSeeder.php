<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'name'    => 'John Doe',
            'email'   => 'customer@email.com ',
            'phone'   => '12345678912',
            'city'    => 'Tunisie',
            'country' => 'Tunis',
            'address' => 'Tunis, Tunisie',
        ]);
    }
}
