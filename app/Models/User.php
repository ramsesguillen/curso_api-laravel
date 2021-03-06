<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';

    protected $table = 'users';
    protected $dates = ['deleted_at'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
        ''
    ];

    // Mutadores y accesores
    // el attributo name sera transformado a minuscula
    public function setNameAttribute( $valor )
    {
        $this->attributes['name'] = strtolower( $valor );
    }

    // Al recuperar el name, este estará capitalizado
    public function getNameAttribute( $valor )
    {
        return ucwords( $valor );
    }

    public function setEmailAttribute( $valor )
    {
        $this->attributes['email'] = strtolower( $valor );
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function esVerificado()
    {
        return $this->verified == User::USUARIO_VERIFICADO;
    }


    public function esAdministrador()
    {
        return $this->verified == User::USUARIO_ADMINISTRADOR;
    }


    public static function generarVerificationToken()
    {
        return Str::random(40);
    }
}
