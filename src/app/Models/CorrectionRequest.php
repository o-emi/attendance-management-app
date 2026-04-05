<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CorrectionRequestBreakTime;

class CorrectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'attendance_id',
    'start_time',
    'end_time',
    'note',
    'status',
    'break_times',
    ];

    protected $casts = [
        'break_times' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function breakTimes()
    {
        return $this->hasMany(CorrectionRequestBreakTime::class);
    }
}
