<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Setores
 *
 * @property int $id
 * @property int $sec_id
 * @property string $name
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $employees
 * @property-read int|null $employees_count
 * @property-read \App\Secretarias $secretaria
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setores newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setores newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setores query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setores whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setores whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setores whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setores whereSecId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setores whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setores whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Setores extends Model
{
    protected $fillable = [
        'sec_id',
        'name',
        'status'
    ];
    protected $table = 'setores';

    public function secretaria() {
        return  $this->belongsTo(Secretarias::class, 'sec_id');
    }

    public function employees(){
        return $this->hasMany(User::class, 'setor_id');
    }
}
