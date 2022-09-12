<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
//the controller interacts with the user (eloquent model)
//controller creates the model via eloquent and retrieve data via the models
//when creating a data row, the model specifies the fillable col (cols that can be inserted to db) 
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ 
        //only the cols in the array can be filled (insertable value)
        //other cols not mentioned cannot be filled during creation of row
        //extra protection of db
        'name',
        'email',
        'username', 
        //we need to add this new col --> else we cannot reference the username when we access 
        //the data row
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile(){
        //establish bi-directional rs (where profile can retrieve user and vice versa)
        //because user is a FK, there for it does not belongs to profile
        //profile belongs to user, user has a profile
        return $this->hasOne(Profile::class);
    }

    //establish a relationship with posts
    //one to many (naming convention = plural)
    public function posts(){
        return $this->hasMany(Post::class);
    }


}
