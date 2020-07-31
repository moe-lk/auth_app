<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public const CREATED_AT = 'created';
    public const UPDATED_AT = 'modified';
    /**
     * The database table used by the model.
     *
     * @var string
     */

    public $timestamps = true;

    protected $table = 'security_users';



    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'openemis_no',
        'first_name',
        'last_name',
        'address',
        'address_area_id',
        'birthplace_area_id',
        'gender_id',
        'remember_token',
        'date_of_birth',
        'nationality_id',
        'identity_type_id',
        'identity_number',
        'is_student',
        'modified_user_id',
        'modified',
        'created_user_id',
        'created',
        'username',
        'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'modified_user_id',
        'middle_name',
        'third_name',
        'modified',
        'created_user_id',
        'created'

    ];


  

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function institutionStudents()
    {
        return $this->hasOne(Institution_student::class, 'student_id');
    }

    public function institutionStudentsClass()
    {
        return $this->hasOne(Institution_student::class, 'student_id');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date_of_birth', 'date_of_death', 'last_login', 'modified', 'created'];

    public function rules()
    {
        return [
            'identity_number' => [
                'required',
                'unique:security_users,identity_number',
            ]
        ];
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function uploads()
    {
        return $this->hasMany('App\Models\Upload');
    }

    public function class()
    {
        return $this->belongsTo('App\Institution_class_student', 'id', 'student_id');
    }

    public function principal(){
        return $this->hasMany('App\Security_group_user','security_user_id','id')
            ->where('security_group_users.security_role_id','=',4)
            ->with(['security_group_institution','institution_staff','security_group'  , 'staff_class','institution_group' , 'roles']);
    }

    public function zonal_cordinator(){
        
        return $this->hasMany('App\Security_group_user','security_user_id','id')
            ->where('security_group_users.security_role_id','=',14)
            ->with(['security_group_institution','institution_staff','security_group'  , 'staff_class','institution_group' , 'roles']);
    }

    public function provincial_cordinator(){
        
        return $this->hasMany('App\Security_group_user','security_user_id','id')
            ->where('security_group_users.security_role_id','=',13)
            ->with(['security_group_institution','institution_staff','security_group'  , 'staff_class','institution_group' , 'roles']);
    }
}
