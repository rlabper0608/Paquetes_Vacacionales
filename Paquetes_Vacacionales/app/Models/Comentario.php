<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comentario extends Model {
    protected $table = 'comentario';

    protected $fillable = ['iduser', 'idvacacion', 'comentario'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function vacacion(): BelongsTo {
        return $this->belongsTo(Vacacion::class, 'idvacacion');
    }
}
