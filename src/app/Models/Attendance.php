<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BreakTime;
use App\Models\CorrectionRequest;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_date',
        'clock_in',
        'clock_out',
        'status'
    ];

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'work_date' => 'date',
    ];

    public function user()
    {
    return $this->belongsTo(\App\Models\User::class);
    }

    public function breakTimes()
    {
        return $this->hasMany(BreakTime::class);
    }

    public function getWorkTotalSecondsAttribute()
    {
        if (!$this->clock_in || !$this->clock_out) {
            return 0;
        }

        $work = $this->clock_in
            ->diffInSeconds($this->clock_out);

        return $work - $this->break_total_seconds;
    }

    public function getBreakTotalAttribute()
    {
        if (!$this->break_total_seconds) {
            return null;
        }

        return gmdate('H:i', $this->break_total_seconds);
    }

    public function getWorkTotalAttribute()
    {
        if (!$this->work_total_seconds) {
            return null;
        }

        return gmdate('H:i', $this->work_total_seconds);
    }

    public function correctionRequests()
    {
        return $this->hasMany(CorrectionRequest::class);
    }
}
