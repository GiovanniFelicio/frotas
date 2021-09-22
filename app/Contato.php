<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AuthFuncVehicle
 *
 * @property int $id
 * @property int $func_id
 * @property int $sec_id
 * @property int $vehicle_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthFuncVehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthFuncVehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthFuncVehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthFuncVehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthFuncVehicle whereFuncId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthFuncVehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthFuncVehicle whereSecId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthFuncVehicle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthFuncVehicle whereVehicleId($value)
 * @mixin \Eloquent
 */
class Contato extends Model
{
    protected $fillable = [
        'motivo',
        'mensagem',
        'func_id'
    ];
    protected $table = 'contato';
}
