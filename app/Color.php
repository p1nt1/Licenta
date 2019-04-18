<?php

namespace Colorize;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name_color', 'color_hex'
    ];
}
