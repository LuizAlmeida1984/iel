<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frase extends Model
{
    protected $fillable = [
        'ordem',
        'area_id',
        'texto',
        'tipo'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
