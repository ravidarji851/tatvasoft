<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventRecurence extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table ='event_recurence';
    protected $guarded=['id'];

   
}
