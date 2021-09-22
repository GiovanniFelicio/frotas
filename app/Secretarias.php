<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Secretarias
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $employees
 * @property-read int|null $employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Veiculos[] $veiculos
 * @property-read int|null $veiculos_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Secretarias newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Secretarias newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Secretarias query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Secretarias whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Secretarias whereEmailSec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Secretarias whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Secretarias whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Secretarias whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Secretarias extends Model
{
    protected $fillable = [
        'name',
        'email'
    ];
    protected $table = 'secretarias';

    public function employees(){
        return $this->hasMany(User::class, 'sec_id');
    }
    public function veiculos(){
        return $this->hasMany(Veiculos::class, 'sec_id');
    }
}
