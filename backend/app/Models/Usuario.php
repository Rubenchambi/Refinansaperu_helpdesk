<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable {

    use HasApiTokens, Notifiable;
    protected $guarded = [];

    public function tickets() {
        return $this->hasMany(Ticket::class, 'usuarioReportanteId');
    }

    public function rol() {
        return $this->belongsTo(Role::class, 'rolId');
    }

    public function area() {
    return $this->belongsTo(Area::class, 'area_id');
}
}
