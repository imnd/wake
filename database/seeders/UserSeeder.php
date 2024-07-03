<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(PaymentService $paymentService): void
    {
        $data = [
            'name'  => 'bouquet',
            'email' => fake()->email,
        ];

        User::create(array_merge($data, [
            'password' => Hash::make(substr(md5(microtime()), 0, 10)),
        ]));

        $paymentService->createCustomer($data);
    }
}
