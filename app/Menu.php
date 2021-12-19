<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['id', 'menu_category_id', 'name', 'money'];
    public $timestamps = false;
}
