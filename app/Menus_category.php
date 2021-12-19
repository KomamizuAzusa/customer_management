<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menus_category extends Model
{
    protected $fillable = ['id', 'name'];
    public $timestamps = false;
}
