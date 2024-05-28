<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Patient extends Model
{
    protected $fillable = [
        'username','fullname', 'email','password','phone','address','age','caregiver_id','gender'
    ];   
}
