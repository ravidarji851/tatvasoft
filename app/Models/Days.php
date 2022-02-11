<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Days extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='days';
    protected $guarded=['id'];
}
