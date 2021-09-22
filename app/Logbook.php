<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Logbook
 *
 * @property int $id
 * @property string $dateTimeSai
 * @property string $dateTimeCheg
 * @property int $veiculo
 * @property int $func_id
 * @property int $setor_id
 * @property int $solic_id
 * @property string $origem
 * @property string $kmInicial
 * @property string $destino
 * @property string|null $kmFinal
 * @property string|null $irreguSai
 * @property string|null $irreguCheg
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereDateTimeCheg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereDateTimeSai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereDestino($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereFuncId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereIrreguCheg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereIrreguSai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereKmFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereKmInicial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereOrigem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereSetorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereSolicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Logbook whereVeiculo($value)
 * @mixin \Eloquent
 */
class Logbook extends Model
{
    protected $fillable = [
        'veiculo',
        'dateTimeSai',
        'dateTimeCheg',
        'func_id',
        'setor_id',
        'sec_id',
        'origem',
        'kmInicial',
        'destino',
        'kmFinal',
        'irregu',
        'actions',
        'status'
    ];
    protected $table = 'logbook';
    
    public function vehicle(){
        return $this->belongsTo(Veiculos::class, 'veiculo');
    }
    public function employee(){
        return $this->belongsTo(User::class, 'func_id');
    }
}
