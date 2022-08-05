<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offering extends Model
{
    use HasFactory;
    protected $table = 'offerings';
    protected $fillable = [
        'id',
        'date',
        'value',
        'description',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
