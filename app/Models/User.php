<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;
use App\Mail\NewUserWelcomeMail;

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
        //only the cols in the array can be mass assigned (mass assignment protection)
        //we can use this to remove the fields that we dont want user to modify directly

        //$guarded --> we only specify the guarded fields (the fields specified in this array will not be modified directly)
        //if empty --> all fields are mass assignable


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
    
    protected static function boot(){
        //this method will be called when we boot our model
        //eg: creating a new user 
        parent::boot();
        static::created(
            function($user){//it will give us the created model in the argument
                $user->profile()->create([
                    'title' => $user->username
                ]);
                //send out email
                Mail::to($user->email)->send(new NewUserWelcomeMail());
            }
            

        ); //created becomes an event, gets fired whenever a new user is created
        //we can use this event to hook up a profile once a user is created
    }

    public function profile(){
        //establish bi-directional rs (where profile can retrieve user and vice versa)
        //because user is a FK, there for it does not belongs to profile
        //profile belongs to user, user has a profile
        return $this->hasOne(Profile::class);
    }

    //establish a relationship with posts
    //one to many (naming convention = plural)
    public function posts(){
        //returns a relationship with post
        return $this->hasMany(Post::class)->orderBy('created_at','DESC');
    }

    //user has many followings
    //user's following belongs to many profile
    public function following(){
        return $this->belongsToMany(Profile::class);
    }

}