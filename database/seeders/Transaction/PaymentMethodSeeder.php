<?php

namespace Database\Seeders\Transaction;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payment_method = PaymentMethod::create([
            'name' => "Midtrans",
            'description' => "Midtrans",
        ]);
    }
}
