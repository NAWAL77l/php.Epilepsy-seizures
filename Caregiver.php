<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Caregiver extends Model
{
 
    protected $fillable = [
        'username','fullname', 'email','password','phone','gender'
    ];         
}
