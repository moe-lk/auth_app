<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Security_group_user extends Model
{
    public const CREATED_AT = 'created';
    public const UPDATED_AT = 'modified';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'security_group_users';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['security_group_id', 'security_user_id', 'security_role_id', 'created_user_id', 'created'];

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
    protected $dates = ['modified', 'created', 'created'];


    public function security_user(){
        return $this->belongsToMany('App\User','security_users');
    }

    public function security_group(){
        return $this->hasMany('App\Security_group' , 'id','security_group_id');
    }

    public function security_group_institution(){
        return $this->belongsTo('App\Security_group_institution','security_group_id','security_group_id');
    }

    public function staff_class(){
        return $this->hasMany('App\Institution_class','staff_id','security_user_id');
    }

    public function institution_staff(){
        return $this->belongsTo('App\Institution_staff','security_user_id','staff_id');
    }

    public function institution_group(){
        return $this->hasMany('App\Security_group_institution','security_group_id','security_group_id')
            ->with(['institution','institution_classes']);
    }


    public function roles(){
        return $this->belongsTo('App\Security_role','security_role_id','id');
    }
}
