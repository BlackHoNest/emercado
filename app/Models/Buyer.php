<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'last_name',
        'first_name',
        'address',
        'contact_number',
        'username',
        'password',
        'profile_picture'
    ];

    protected $hidden = [
        'password',
    ];

}
