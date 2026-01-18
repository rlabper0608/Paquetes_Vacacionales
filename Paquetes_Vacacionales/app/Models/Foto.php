<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Foto extends Model {
    protected $table = 'foto';

    protected $fillable = ['idvacacion', 'ruta'];

    public function vacacion(): BelongsTo {
        return $this->belongsTo(Vacacion::class, 'idvacacion');
    }
}
