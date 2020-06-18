<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Security_group extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'security_groups';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'modified_user_id', 'modified', 'created_user_id', 'created'];

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
    protected $dates = ['modified', 'created', 'created', 'created', 'modified', 'created'];

//    public function securityUsers(){
//        return $this->hasMany( User::class);
//    }

    public function security_users(){
        return $this->belongsTo('App\Security_group_user','security_group_id');
    }


    public function security_group_institution(){
        return $this->hasMany('App\Security_group_institution','security_group_id');
    }
}
