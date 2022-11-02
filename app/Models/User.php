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

        //$guarded --> we only specify the guarded fields
        //(specify which field should be protected)
        //if empty --> all fields are mass assignable (disable mass assignment protection)
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        //boot method will be called when we create each User data model (each row in db)
        //eg: created method will be invoked each time a new user successfully created (registers)
        parent::boot();
        static::created(function ($user) {
            //it will give us the created model in the argument
            $user->profile()->create([
                //associate each user with a profile
                'title' => $user->name,
            ]);
            //send out email
            Mail::to($user->email)->send(new NewUserWelcomeMail());
        }); //created becomes an event, gets fired whenever a new user is created
        //we can use this event to hook up a profile once a user is created

        static::deleting(function ($user) {
            $user->profile()->delete();
        });
    }

    public function profile()
    {
        //returns a relationship with profile
        //establish bi-directional rs (where profile can retrieve user and vice versa)
        //because user and profile is a 1-1 rs
        //user (parent) has one profile and not vice versa
        return $this->hasOne(Profile::class);
    }

    //one to many (naming convention = plural)
    public function posts()
    {
        //returns a relationship with post
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC'); //returns sorted posts
        //eloquent model provides many query features
    }

    //user can follow many profiles
    //user's following belongs to many profile
    public function following()
    {
        return $this->belongsToMany(Profile::class)
            ->where('status', 'accepted')
            ->with('user'); //optional: takes in second parameter (the pivot table name)
        //if your pivot follows the naming convention (no need to provide)
        //naming convention: alphabetical order + _ (profile_user pivot table name) --> only in many to many relationship
    }

    public function requestFollow()
    {
        return $this->belongsToMany(Profile::class)->where('status', 'pending'); //optional: takes in second parameter (the pivot table name)
        //if your pivot follows the naming convention (no need to provide)
        //naming convention: alphabetical order + _ (profile_user pivot table name) --> only in many to many relationship
    }

    public function liking()
    {
        return $this->belongsToMany(Post::class); //takes in second parameter (the pivot table name)
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
