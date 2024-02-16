<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{
    use HasFactory;
    protected $fillable = [
        'razon_social',
        'ruc',
        'direccion',
        'logo_path',
        'sol_user',
        'sol_pass',
        'cert_path',
        'client_id',
        'client_secret',
        'production'
    ];

    // Relacion uno a muchos
    public function users(){
        return $this->hasMany(User::class);
    }

    // Relacion uno a muchos
    public function ventas(){
        return $this->hasMany(Venta::class);
    }
}
