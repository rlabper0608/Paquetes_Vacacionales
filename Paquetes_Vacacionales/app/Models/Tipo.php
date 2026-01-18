<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tipo extends Model {
    protected $table = 'tipo';

    protected $fillable = ['nombre'];

    public function vacacion(): HasMany {
        return $this->HasMany(Vacacion::class, 'idvacacion');
    }
}
