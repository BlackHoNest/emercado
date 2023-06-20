<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = "Admin";

    public $timestamps = true;

    protected $fillable = [
        'account_id',
        'municipal',
        'province',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
