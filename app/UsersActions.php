<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UsersActions
 *
 * @property int $id
 * @property int $user_id
 * @property int $sec_id
 * @property int $setor_id
 * @property int $action
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UsersActions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UsersActions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UsersActions query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UsersActions whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UsersActions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UsersActions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UsersActions whereSecId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UsersActions whereSetorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UsersActions whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UsersActions whereUserId($value)
 * @mixin \Eloquent
 */
class UsersActions extends Model
{
    protected $fillable = [
        'func',
        'sec_id',
        'setor_id',
        'action'
    ];
    protected $table = 'users_actions';
}
