<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fingerprint extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = ['name', 'serial_number', 'is_active'];
}
