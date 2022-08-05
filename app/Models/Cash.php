<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'cash';
    protected $fillable = [
        'id',
        'date',
        'value',
        'user_id',
        'respuestable_type',
        'respuestable_id',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function respuestable(){
        return $this->morphTo(__FUNCTION__,'respuestable_type','respuestable_id');
    }

}
