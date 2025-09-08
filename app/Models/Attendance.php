<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['schedule_id','employee_id','date','check_in','check_out','overtime_hours','overtime_status'];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
