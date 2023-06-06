<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;

class ClientDeliveryAddress extends Model
{
    protected $fillable = [
      'address',
      'city',
      'region',
      'zip_code'
    ];
}
