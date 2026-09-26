<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ProductSeeder::class);

        User::updateOrCreate(
            ['email' => 'demo@sunaccesories.com'],
            [
                'name' => 'Defne Güneş',
                'password' => 'sifre1234',
                'phone' => '0532 000 00 00',
                'city' => 'İzmir',
                'address' => 'Alsancak Mah. Papatya Sok. No:7 D:3',
            ],
        );
    }
}
