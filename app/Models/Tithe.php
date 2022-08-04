<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tithe extends Model
{
    use HasFactory;
    protected $table = 'tithes';
    protected $fillable = [
        'id',
        'date',
        'value',
        'user_id',
        'member_id',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function member(){
        return $this->belongsTo('App\Models\Member','member_id','id');
    }
}
