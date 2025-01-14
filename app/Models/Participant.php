<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'instruments',
        'other',
        'is_active'
    ];

    protected $casts = [
        'instruments' => 'array',
        'is_active' => 'boolean'
    ];
}
