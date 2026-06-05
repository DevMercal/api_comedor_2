<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLogs extends Model
{
    protected $fillable = [
        'entity',
        'entity_id',
        'action',
        'old_values',
        'new_values',
        'user_id',
        'ip_address'
    ];

    // Esto convierte el JSON de la DB en un array de PHP automáticamente
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // Relación para saber qué administrador hizo el cambio
    public function causer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        // Relacionamos user_id del log con el id de la tabla users
        return $this->belongsTo(User::class, 'user_id');
    }
}
