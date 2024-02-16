<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organizacion>
 */
class OrganizacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'razon_social' => 'Coders Free S.A.C',
            'ruc' => 20609278235,
            'direccion' => 'Calle Falsa 123',
            'sol_user' => 'prueba',// Usuario SOL 
            'sol_pass' =>  'prueba'// Clave SOL
        ];
    }
}
