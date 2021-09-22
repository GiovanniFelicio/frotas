<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UltimosAcessos
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $last_login
 * @property string $last_login_ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UltimosAcessos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UltimosAcessos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UltimosAcessos query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UltimosAcessos whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UltimosAcessos whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UltimosAcessos whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UltimosAcessos whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UltimosAcessos whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UltimosAcessos whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UltimosAcessos whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UltimosAcessos extends Model
{
    protected $fillable = [
        'name',
        'email',
        'last_login',
        'last_login_ip'

    ];
    protected $table = 'ultimo_acesso';
}
