<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\GastosVeiculos
 *
 * @property int $id
 * @property int $vehicle_id
 * @property int $sec_id
 * @property string $item
 * @property string $valor
 * @property string $data
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Veiculos $veiculos
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GastosVeiculos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GastosVeiculos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GastosVeiculos query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GastosVeiculos whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GastosVeiculos whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GastosVeiculos whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GastosVeiculos whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GastosVeiculos whereSecId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GastosVeiculos whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GastosVeiculos whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GastosVeiculos whereValor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GastosVeiculos whereVehicleId($value)
 * @mixin \Eloquent
 */
class GastosVeiculos extends Model
{
    protected $fillable = [
        'sec_id',
        'vehicle_id',
        'item',
        'valor',
        'data',
        'status',
    ];
    protected $table = 'gastos_veiculos';

    public function veiculos(){
        return $this->belongsTo(Veiculos::class, 'vehicle_id');
    }
}
