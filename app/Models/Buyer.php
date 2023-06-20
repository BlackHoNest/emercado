<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use SoftDeletes;

    protected $table = "Buyer";

    public $timestamps = true;

    protected $fillable = [
        'buyer_id',
        'last_name',
        'first_name',
        'contact_number',
        'birth_date',
        'gender',
        'province',
        'municipality',
        'barangay',
        'street',
        'zipcode',
        'profile_picture'
    ];

    protected $hidden = [
        'password',
    ];

}
