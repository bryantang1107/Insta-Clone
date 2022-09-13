<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];
    //tell laravel its okay to not guard anything
    //allow fillable = all 


    public function user(){
        return $this->belongsTo(User::class);
    }
}
