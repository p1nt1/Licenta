<?php

namespace Colorize;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $fillable = [
        'color', 'user', 'photo'
    ];

    public function color(){
        return $this->belongsTo('Colorize\Color', 'color');
    }

    public function user(){
        return $this->belongsTo('Colorize\Client', 'user');
    }
}
