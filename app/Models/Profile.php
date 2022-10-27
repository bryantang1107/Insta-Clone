<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $guarded = []; //disabling mass assignment protection
    //telling eloquent model to guard no columns

    //$fillable --> only specified values are mass assignable (assign value to model at one go)

    public function user()
    {
        return $this->belongsTo(User::class);
        //because profile is a child table (has FK user_id), we need to include FK in belongsTo
        //belongsTo provide another parameter (foreignKey & ownerKey)
        //foreignKey --> explicitly provide the fk name (no need to provide if we follow the naming convention)
        //naming convention of fk (user_id) --> same as parent table name (user)
    }
    //profile has many followers
    //profile's followers belongs to many user
    public function followers()
    {
        return $this->belongsToMany(User::class)->where('status', 'accepted');
    }
    public function followerRequestList()
    {
        return $this->belongsToMany(User::class);
    }
}
