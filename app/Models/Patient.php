<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'doctor_id',
        'phoneNumber',
        'date_of_birth',
        'age',
    ];
    public $timestamps = false;
}
