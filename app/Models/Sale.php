<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sales';

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
    protected $fillable = [
        'aso_id',
        'dbid',
        'order_number',
        'asm_rsm_id',
        'order_id',
        'order_date',
        'sale_date',
        'total_sale_amount',
        'sender_name',
        'sender_phone',
//        'dh_phone',
//        'dh_name',
//        'tso_name',
//        'tso_phone',
        'sale_type',
        'sale_total_sku',
        'sale_total_case',
        'sale_route_id',
        'sale_route',
        'created_by'
    ];

    public static function insertSale($sale,$sale_details){
        try{
            DB::beginTransaction();
            $sale= Sale::Create($sale);
            foreach ($sale_details as $key=>$value){
                $sale_details[$key]['sales_id']=$sale->id;
            }
            SaleDetail::insert($sale_details);
            DB::commit();
            return true;
        }catch(\Exception $e){
            dd($e);
            DB::rollBack();
            return false;
        }

    }
}
