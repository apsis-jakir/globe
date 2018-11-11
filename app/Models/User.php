<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

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
                  'name',
                  'email',
                  'password',
                  'password_key',
                  'password_expire_days',
                  'last_name',
                  'mobile',
                  'home_telephone',
                  'username',
                  'secret_question_1',
                  'secret_question_2',
                  'secret_question_ans_1',
                  'secret_question_ans_2',
                  'identification_type_id',
                  'identification_number',
                  'identification_expire_date',
                  'date_of_birth',
                  'gender',
                  'religion_id',
                  'father_name',
                  'father_occupation_id',
                  'mother_name',
                  'mother_occupation_id',
                  'bank_account_number',
                  'bank_id',
                  'bank_branch',
                  'last_login_date_time',
                  'default_module_id',
                  'user_image',
                  'address',
                  'is_reliever',
                  'reliever_to',
                  'reliever_start_datetime',
                  'reliever_end_datetime',
                  'line_manager_id',
                  'designation_id',
                  'department_id',
                  'location_id',
                  'default_vehicle_id',
                  'default_url',
                  'default_language_id',
                  'joining_date',
                  'emergency_contact_person_name',
                  'emergency_contact_relation',
                  'emergency_contact_number',
                  'remember_token',
                  'territories_id',
                  'distribution_houses_id',
                  'rfu1',
                  'rfu2',
                  'zones_id',
                  'created_by',
                  'updated_by',
                  'status'
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
     * Get the identificationType for this model.
     */
    public function identificationType()
    {
        return $this->belongsTo('App\Models\IdentificationType','identification_type_id');
    }

    /**
     * Get the religion for this model.
     */
    public function religion()
    {
        return $this->belongsTo('App\Models\Religion','religion_id');
    }

    /**
     * Get the fatherOccupation for this model.
     */
    public function fatherOccupation()
    {
        return $this->belongsTo('App\Models\FatherOccupation','father_occupation_id');
    }

    /**
     * Get the motherOccupation for this model.
     */
    public function motherOccupation()
    {
        return $this->belongsTo('App\Models\MotherOccupation','mother_occupation_id');
    }

    /**
     * Get the bank for this model.
     */
    public function bank()
    {
        return $this->belongsTo('App\Models\Bank','bank_id');
    }

    /**
     * Get the defaultModule for this model.
     */
    public function defaultModule()
    {
        return $this->belongsTo('App\Models\DefaultModule','default_module_id');
    }

    /**
     * Get the lineManager for this model.
     */
    public function lineManager()
    {
        return $this->belongsTo('App\Models\LineManager','line_manager_id');
    }

    /**
     * Get the designation for this model.
     */
    public function designation()
    {
        return $this->belongsTo('App\Models\Designation','designation_id');
    }

    /**
     * Get the department for this model.
     */
    public function department()
    {
        return $this->belongsTo('App\Models\Department','department_id');
    }

    /**
     * Get the location for this model.
     */
    public function location()
    {
        return $this->belongsTo('App\Models\Location','location_id');
    }

    /**
     * Get the defaultVehicle for this model.
     */
    public function defaultVehicle()
    {
        return $this->belongsTo('App\Models\DefaultVehicle','default_vehicle_id');
    }

    /**
     * Get the defaultLanguage for this model.
     */
    public function defaultLanguage()
    {
        return $this->belongsTo('App\Models\DefaultLanguage','default_language_id');
    }

    /**
     * Get the territory for this model.
     */
    public function territory()
    {
        return $this->belongsTo('App\Models\Territory','territories_id');
    }

    /**
     * Get the distributionHouse for this model.
     */
    public function distributionHouse()
    {
        return $this->belongsTo('App\Models\DistributionHouse','distribution_houses_id');
    }

    /**
     * Get the zone for this model.
     */
    public function zone()
    {
        return $this->belongsTo('App\Models\Zone','zones_id');
    }

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
     * Set the identification_expire_date.
     *
     * @param  string  $value
     * @return void
     */
    public function setIdentificationExpireDateAttribute($value)
    {
        $this->attributes['identification_expire_date'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Set the date_of_birth.
     *
     * @param  string  $value
     * @return void
     */
    public function setDateOfBirthAttribute($value)
    {
        $this->attributes['date_of_birth'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Set the last_login_date_time.
     *
     * @param  string  $value
     * @return void
     */
    public function setLastLoginDateTimeAttribute($value)
    {
        $this->attributes['last_login_date_time'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Set the reliever_start_datetime.
     *
     * @param  string  $value
     * @return void
     */
    public function setRelieverStartDatetimeAttribute($value)
    {
        $this->attributes['reliever_start_datetime'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Set the reliever_end_datetime.
     *
     * @param  string  $value
     * @return void
     */
    public function setRelieverEndDatetimeAttribute($value)
    {
        $this->attributes['reliever_end_datetime'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Set the joining_date.
     *
     * @param  string  $value
     * @return void
     */
    public function setJoiningDateAttribute($value)
    {
        $this->attributes['joining_date'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Get identification_expire_date in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getIdentificationExpireDateAttribute($value)
    {
        return date('j/n/Y', strtotime($value));
    }

    /**
     * Get date_of_birth in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getDateOfBirthAttribute($value)
    {
        return date('j/n/Y', strtotime($value));
    }

    /**
     * Get last_login_date_time in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getLastLoginDateTimeAttribute($value)
    {
        return date('j/n/Y g:i A', strtotime($value));
    }

    /**
     * Get reliever_start_datetime in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getRelieverStartDatetimeAttribute($value)
    {
        return date('j/n/Y g:i A', strtotime($value));
    }

    /**
     * Get reliever_end_datetime in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getRelieverEndDatetimeAttribute($value)
    {
        return date('j/n/Y g:i A', strtotime($value));
    }

    /**
     * Get joining_date in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getJoiningDateAttribute($value)
    {
        return date('j/n/Y', strtotime($value));
    }

    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return date('j/n/Y g:i A', strtotime($value));
    }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUpdatedAtAttribute($value)
    {
        return date('j/n/Y g:i A', strtotime($value));
    }

}
