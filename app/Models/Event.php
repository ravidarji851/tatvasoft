<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table='event';
    protected $guarded=['id'];

    // public function setStartDateAttribute($value){
    // 	$this->attributes['start_date']=!(empty($value)) ? date('y-m-d') : null;
    // }
    // public function setEndDateAttribute($value){
    // 	$this->attributes['end_date']=!(empty($value)) ? date('y-m-d') : null;
    // }
    public function get_reccurence(){
        return $this->hasOne('App\Models\EventRecurence','event_id');
    }
    public function get_date(){
        return $this->hasMany('App\Models\EventDate','event_id','id');
    }
}
