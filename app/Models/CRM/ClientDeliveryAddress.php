<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CRM\ClientDeliveryAddress
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDeliveryAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDeliveryAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientDeliveryAddress query()
 * @mixin \Eloquent
 * @mixin IdeHelperClientDeliveryAddress
 */
class ClientDeliveryAddress extends Model
{
    protected $fillable = [
      'address',
      'city',
      'region',
      'zip_code'
    ];
}
