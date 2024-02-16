<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;
    protected $fillable = [
        'ruc',
        'razonSocial',
        'codMotivo',
        'desMotivo',
        'tipoMoneda',
        'serie',
        'correlativo',
        'tipDocAfectado',
        'numDocfectado',
        'direccion',
        'fechaEmision',
        'fechaVencimiento',
        'subTotal', 
        'mtoIGV',
        'mtoImpVenta',
        'tipoOperacion',
        'productos',
        'tipoDoc',
        'mtoOperGravadas',
        'mtoOperExoneradas',
        'mtoOperInafectas',
        'mtoOperExportacion',
        'mtoOperGratuitas',
        'mtoIGVGratuitas',
        'icbper',
        'totalImpuestos',
        'valorVenta',
        'redondeo',
        'legends',
        'organizacion_id',
        'user_id',
        'XML',
        'CDR',
        'cdrResponse',
        'estadoSunat' 
    ];

    // Relacion uno a muchos inversa
    public function organizacion(){
        return $this->belongsTo(Organizacion::class);
    }

    // Relacion uno a muchos inversa
    public function user(){
        return $this->belongsTo(User::class);
    }
}
