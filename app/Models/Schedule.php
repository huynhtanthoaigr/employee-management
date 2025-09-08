<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['employee_id', 'date', 'shift_id'];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'schedule_id');
    }


}
