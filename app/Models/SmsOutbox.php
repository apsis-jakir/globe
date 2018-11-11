<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsOutbox extends Model
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
    protected $table = 'sms_outboxes';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * fillable
     * @var array
     */
    protected $fillable=[
        'inbox_id',
        'sms_receiver_number',
        'sms_content',
        'priority',
        'order_type'
    ];
}
