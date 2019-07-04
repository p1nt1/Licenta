<?php

namespace Colorize;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'description', 'size','picture' , 'price' , 'user_id' ,'status'
    ];
}
