<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sms extends Model
{
    //table name
    protected  $tables="sms_inboxes";
    //primary key
    protected  $primaryKeys ="id";
    //fillable
    protected $fillable=[

    ];

    public function getSms($id){
         return DB::table($this->tables)->where($this->primaryKeys,$id)->first();
    }
}
