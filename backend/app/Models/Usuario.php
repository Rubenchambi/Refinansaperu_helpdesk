<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'nombre', 
        'apellidos', 
        'dni', 
        'email', 
        'password', 
        'rolId', 
        'estado',
        'intentos_fallidos',
        'bloqueado_hasta'
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function areas()
    {
      return $this->belongsToMany(Area::class, 'usuario_area', 'usuario_id', 'area_id');
    }

    public function role() 
    {
        return $this->belongsTo(Role::class, 'rolId');
    }
}