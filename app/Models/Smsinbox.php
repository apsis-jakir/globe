<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsInbox extends Model
{
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sms_inboxes';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'sender',
                  'transactionId',
                  'sms_content',
                  'sms_status',
                  'reason',
                  'sms_received',
                  'is_active'
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
     * Get the creator for this model.
     */
    public function creator()
    {
        return $this->belongsTo('App\User','created_by');
    }

    /**
     * Get the updater for this model.
     */
    public function updater()
    {
        return $this->belongsTo('App\User','updated_by');
    }

    /**
     * Set the created.
     *
     * @param  string  $value
     * @return void
     */
    public function setCreatedAttribute($value)
    {
        $this->attributes['created'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Set the updated.
     *
     * @param  string  $value
     * @return void
     */
    public function setUpdatedAttribute($value)
    {
        $this->attributes['updated'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Get created in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAttribute($value)
    {
        return date('j/n/Y g:i A', strtotime($value));
    }

    /**
     * Get updated in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUpdatedAttribute($value)
    {
        return date('j/n/Y g:i A', strtotime($value));
    }

}
