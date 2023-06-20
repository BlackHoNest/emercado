<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmTypeSub extends Model
{
    // use softDeletes;

    protected $table = "farmtype_sub";

    protected $fillable = [
        'product_description',
        'remarks',
        'farmtypeid'
    ];

}
