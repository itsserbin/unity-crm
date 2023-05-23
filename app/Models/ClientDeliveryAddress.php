<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
