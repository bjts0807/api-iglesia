<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $table = 'members';
    protected $fillable = [
        'id',
        'first_name',
        'second_name',
        'first_surname',
        'second_surname',
        'document_type',
        'document_number',
        'phone',
        'address',
        'email',
        'birthday',
        'active_member',
        'active'
    ];

    /* public function preguntas(){
        return $this->hasMany('App\Models\PreguntasFormulario','id_etapa', 'id');
    } */
}
