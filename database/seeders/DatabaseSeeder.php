<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Organizacion;
use App\Models\Pais;
use App\Models\Persona;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        //Pais::factory(5)->create();
        Organizacion::factory(1)->create();
        //Persona::factory(100)->create();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
    }
}
