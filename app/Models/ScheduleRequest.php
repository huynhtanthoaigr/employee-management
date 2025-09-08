<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleRequest extends Model {
    protected $fillable = ['employee_id','date','shift_id','status'];

    public function employee() {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function shift() {
        return $this->belongsTo(Shift::class);
    }
}
