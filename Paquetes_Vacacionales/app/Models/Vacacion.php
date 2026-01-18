<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacacion extends Model {
    protected $table = 'vacacion';

    protected $fillable = ['titulo', 'descripcion', 'precio', 'idtipo'];

    public function foto(): HasMany {
        return $this->hasMany(Foto::class, 'idfoto');
    }

    public function tipo(): BelongsTo {
        return $this->belongsTo(Tipo::class, 'idtipo');
    }

    public function comentario(): HasMany {
        return $this->hasMany(Comentario::class, 'idvacacion');
    }

    public function reserva(): HasMany {
        return $this->HasMany(Reserva::class, 'idvacacion');
    }
}
