<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'seller_id',
        'farm_province',
        'farm_municipality',
        'farm_barangay',
        'farm_coordinates',
        'farm_size',
        'farm_type',
        'farm_crops',
        'farm_livestocks',
        'farm_vegetables',
        'farm_products',
        'gross_harvest',
        'net_harvest',
        'beneficiary',
        'beneficiary_specify',
        'aid_received',
    ];
}
