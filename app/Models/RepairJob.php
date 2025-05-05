<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairJob extends Model
{
    protected $fillable = ['equipment_id', 'technician_id', 'status', 'tasks', 'cost'];

    // Define relationship to Equipment
    public function equipment()
    {
        return $this->belongsTo(Equipments::class);
    }

    // Define relationship to Technician (User)
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
