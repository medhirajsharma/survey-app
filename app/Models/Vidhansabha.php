<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vidhansabha extends Model
{
    use HasFactory;

    protected $fillable = [
        'constituency_no',
        'constituency_name',
    ];
}
