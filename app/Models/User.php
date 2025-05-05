<?php

// App\Models\User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    public function equipments()
{
    return $this->belongsToMany(Equipment::class);
}

public function repairJobs()
{
    return $this->hasMany(RepairJob::class, 'technician_id');
}

}
