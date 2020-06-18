<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution_staff extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'institution_staff';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['FTE', 'start_date', 'start_year', 'end_date', 'end_year', 'staff_id', 'staff_type_id', 'staff_status_id', 'institution_id', 'institution_position_id', 'security_group_user_id', 'modified_user_id', 'modified', 'created_user_id', 'created', 'area_administrative_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date', 'end_date', 'modified', 'created'];


    public function staff_class(){
        return $this->hasMany('App\Models\Institution_class','staff_id','staff_id');
    }

    public function institution(){
        return $this->belongsTo('App\Models\Institution','institution_id');
    }
}
