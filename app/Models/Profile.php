<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $guarded = [];//disabling mass assignment protection
    //telling eloquent model to guard no columns 

    //$fillable --> only specified values are mass assignable (assign value to model at one go)

    //profile will be able to fetch user based on "user_id" --> fk
    public function user(){
        //tells laravel to create a relationship between user and profile
        return $this->belongsTo(User::class);
        //belongsTo provide another parameter (foreignKey & ownerKey)
        //foreignKey --> explicitly provide the fk name (no need to provide if we follow the naming convention)
        //naming convetion --> same as db name 
    }
    //profile has followers
    //profile's followers belongs to many user
    public function followers(){
        return $this->belongsToMany(User::class);
    }
}
