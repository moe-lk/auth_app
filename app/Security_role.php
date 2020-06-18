<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Security_role extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'security_roles';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'code', 'order', 'visible', 'security_group_id', 'modified_user_id', 'modified', 'created_user_id', 'created'];

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
    protected $dates = ['modified', 'created'];


    public function securityUsers(){
        return $this->belongsToMany(Security_group_user::class,'security_group_users','security_group_id','security_group_id');
    }
}
