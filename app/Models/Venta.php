<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Venta extends Model
{
    use HasFactory;
    protected $fillable = [
        'ruc',
        'razonSocial',
        'tipoMoneda',
        'serie',
        'correlativo',
        'direccion',
        'fechaEmision',
        'fechaVencimiento',
        'subtotal', 
        'mtoIGV',
        'mtoImpVenta',
        'tipoOperacion',
        'productos',
        'tipoDoc',
        'formaPago',
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

    /*protected function getFechaEmisionAttribute(): Attribute
    {
        return new Attribute(
            set: function($value){
                return date_format($value,"d-m-Y");
            }
        );
    }*/
}
