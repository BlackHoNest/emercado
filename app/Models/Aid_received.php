<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aid_received extends Model
{
    use HasFactory;

    protected $table = "Aid_received";

    public $timestamps = true;

    protected $fillable = [
        'seller_id',
        'aid_id',
    ];
}
