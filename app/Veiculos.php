<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Veiculos
 *
 * @property int $id
 * @property string $sec_id
 * @property string $nameVei
 * @property string $placa
 * @property int $number
 * @property string $km
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GastosVeiculos[] $gastos
 * @property-read int|null $gastos_count
 * @property-read \App\Secretarias $secretarias
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Veiculos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Veiculos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Veiculos query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Veiculos whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Veiculos whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Veiculos whereKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Veiculos whereNameVei($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Veiculos whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Veiculos wherePlaca($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Veiculos whereSecId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Veiculos whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Veiculos whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Veiculos extends Model
{
    protected $fillable = [
        'sec_id',
        'name',
        'placa',
        'km',
        'status'
    ];
    protected $table = 'veiculos';

    public function secretaria(){
        return $this->belongsTo(Secretarias::class, 'sec_id');
    }
    public function gastos(){
        return $this->hasMany(GastosVeiculos::class, 'vehicle_id');
    }
    public function logbook(){
        return $this->hasMany(Logbook::class, 'veiculo');
    }
}
