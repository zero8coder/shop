<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'pid',
        'name',
        'url',
        'sort'
    ];
}
