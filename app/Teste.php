<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teste extends Model
{
    protected $fillable = [
        'biometria'
    ];
    protected $table = 'teste';
}
