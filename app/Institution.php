<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'institutions';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'alternative_name', 'code', 'address', 'postal_code', 'contact_person', 'telephone', 'fax', 'email', 'website', 'date_opened', 'year_opened', 'date_closed', 'year_closed', 'longitude', 'latitude', 'logo_name', 'logo_content', 'shift_type', 'classification', 'area_id', 'area_administrative_id', 'institution_locality_id', 'institution_type_id', 'institution_ownership_id', 'institution_status_id', 'institution_sector_id', 'institution_provider_id', 'institution_gender_id', 'security_group_id', 'modified_user_id', 'modified', 'created_user_id', 'created'];

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
    protected $dates = ['date_opened', 'date_closed', 'modified', 'created'];

    public function isActive($id){
       return  self::query()->find($id)->get()->first()->institution_status_id == 1;
    }
}
