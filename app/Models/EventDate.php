<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EventDate extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table='event_date';
    protected $guarded=['id'];

    //  public function setDateAttribute($value){
    // 	$this->attributes['date']=!(empty($value)) ? date('y-m-d') : null;
    // }
}
