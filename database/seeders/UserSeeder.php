<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(range(1, 10) as $i) {
            User::create([
                'name' => 'Teste ' . $i,
                'email' => 'teste' . $i . '@teste.com',
                'password' => bcrypt('123456'),
            ]);
        }
    }
}
