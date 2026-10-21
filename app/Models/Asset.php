<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_tag',
        'name',
        'type',
        'category',
        'brand',
        'model',
        'serial_number',
        'assigned_user',
        'location',
        'status',
        'purchase_date',
        'warranty_expiry',
        'remarks',
    ];
}