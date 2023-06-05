<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $table = "sellers";
    protected $fillable = [
        'id',
        'last_name',
        'first_name',
        'middle_name',
        'birthdate',
        'gender',
        'civil_status',
        'contact_number',
        'education',
        'province',
        'municipality',
        'barangay',
        'street',
        'zipcode',
        'username',
        'password',
        'idnumber',
        'idphoto',
        'profile_picture'
    ];

    protected $hidden = [
        'password',
    ];
}
