<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sale_details';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'orders_id',
//        'route_name',
//        'total_outlet',
//        'visited_outlet',
//        'order_details',
//        'created_by'
//    ];
}
