<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareLicense extends Model
{
    use HasFactory;

    protected $table = 'software_licenses';

    protected $fillable = [
        'software_tag',
        'software_name',
        'category',
        'vendor',
        'version',
        'license_type',
        'total_seats',
        'assigned_seats',
        'available_seats',
        'license_key',
        'admin_portal_url',
        'purchase_date',
        'expiration_date',
        'billing_cycle',
        'cost',
        'invoice_number',
        'enable_email_alert',
        'department',
        'assigned_user',
        'status',
        'attachment_path',
        'remarks',
    ];
}