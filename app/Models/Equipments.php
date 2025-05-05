<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipments extends Model
{
    use HasFactory;

    protected $table = 'equipments'; 

    protected $fillable = [
        'customer_id',
        'name',
        'description',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

   
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

  
    public function repairJobs()
    {
        return $this->hasMany(RepairJob::class);
    }

  
    public function getCustomerNameAttribute()
    {
        return $this->customer->name ?? 'Guest';
    }
    public function users()
{
    return $this->belongsToMany(User::class);
}

public function technician()
{
    return $this->belongsTo(User::class, 'technician_id');
}


public function latestRepairJobStatus()
{
    $repairJob = $this->repairJobs()->latest()->first();
    return $repairJob ? $repairJob->status : $this->status;
}

}
