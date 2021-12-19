<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['id', 'first_name', 'last_name', 'number', 'first_name_kana', 'last_name_kana', 'birthday', 'start', 'gender'];
    public $timestamps = false;
}
