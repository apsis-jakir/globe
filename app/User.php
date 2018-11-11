<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MailResetPasswordToken;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','password_key ','password_expire_days','last_name','mobile','home_telephone','username','secret_question_1',
        'secret_question_2','secret_question_ans_1','secret_question_ans_2','identification_type_id','identification_number','identification_expire_date',
        'date_of_birth','gender','religion_id','father_name','father_occupation_id','mother_name','mother_occupation_id','bank_account_number','bank_id',
        'bank_branch','last_login_date_time','default_module_id','created_by','updated_by','status','user_image','address','is_reliever','reliever_to',
        'reliever_start_datetime','reliever_end_datetime','line_manager_id','designation_id','department_id','location_id','default_vehicle_id',
        'default_url','default_language_id','joining_date','emergency_contact_person_name','emergency_contact_relation','emergency_contact_number',
        'remember_token','created_at','updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token));
    }
}
