<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = ['id', 'name', 'name_kana', 'is_deleted'];
    public $timestamps = false;
}
