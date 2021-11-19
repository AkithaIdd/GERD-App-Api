<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patientId',
        'date_of_test',
        // 'age_of_onset',
        'age_of_patient',
        'length_of_les',
    ];
    public $timestamps = false;
    protected $table = 'patients_records';
}
