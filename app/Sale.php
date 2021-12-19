<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'id', 'date', 'profit', 'memo', 'image1', 'image2', 'image3', 'image4', 'image5',
        'customer_id', 'employee_id', 'store_id', 'gender', 'menu_id', 'incident_date',
        'incident_content', 'incident_happened', 'updated_on', 'is_deleted'
    ];
    public $timestamps = false;
}
