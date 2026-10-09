<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Challenge extends Model
{
    protected $fillable = ['theme', 'description', 'categorie', 'img_primary', 'starting_date', 'finishing_date', 'actif'];

    protected $casts = [
        'starting_date' => 'datetime',
        'finishing_date' => 'datetime',
        'actif' => 'boolean',
    ];

    public $timestamps = false;

    public function participations(): HasMany
    {
        return $this->hasMany(
            Participation::class,
            'id_challenge'
        );
    }
}
