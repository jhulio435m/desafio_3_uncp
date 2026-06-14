<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CatalogSeeder::class);

        User::updateOrCreate([
            'email' => 'admin@uncp.edu.pe',
        ], [
            'name' => 'Administrador UNCP',
            'password' => Hash::make('uncp123456'),
        ]);
    }
}
