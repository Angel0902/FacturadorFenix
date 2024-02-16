<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Angel Atencio',
            'email'=>'administrador@gmail.com',
            'organizacion_id'=>1,
            'password'=>bcrypt('12345678')
        ])->assignRole('Administrador');

        /*User::create([
            'name'=>'Alberto Caldera',
            'email'=>'atencio_an@hotmail.com',
            'organizacion_id'=>1,
            'password'=>bcrypt('12345678')
        ])->assignRole('Usuario');*/
    }
}
