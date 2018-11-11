<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionHouse extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'distribution_houses';

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
        'zones_id',
        'regions_id',
        'territories_id',
        'market_name',
        'code',
        'point_name',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the zone for this model.
     */
    public function zone()
    {
        return $this->belongsTo('App\Models\Zone', 'zones_id');
    }

    /**
     * Get the region for this model.
     */
    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'regions_id');
    }

    /**
     * Get the territory for this model.
     */
    public function territory()
    {
        return $this->belongsTo('App\Models\Territory', 'territories_id');
    }


}
