<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Musician extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'instruments',
        'other',
        'vocalist',
        'is_active'
    ];

    protected $casts = [
        'instruments' => 'array',
        'vocalist' => 'boolean',
        'is_active' => 'boolean'
    ];
}
