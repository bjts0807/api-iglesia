<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $table = 'expenses';
    protected $fillable = [
        'id',
        'date',
        'value',
        'concept',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
