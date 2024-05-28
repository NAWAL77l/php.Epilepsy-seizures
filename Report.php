<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Report extends Model
{
    protected $fillable = [
        'patient_id','caregiver_id','datetime','status','details'
    ];      
}
