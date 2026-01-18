<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model {
    protected $table = 'reserva';

    protected $fillable = ['iduser', 'idvacacion', 'fecha_reserva'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function vacacion(): BelongsTo {
        return $this->belongsTo(Vacacion::class, 'idvacacion');
    }
}
