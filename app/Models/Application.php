<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Application extends Model
{
    /** @use HasFactory<\Database\Factories\ApplicationFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = ['student_id', 'schedule_id', 'subject', 'status', 'reason', 'application_date'];

    // Un estudiante puede tener muchas solicitudes
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    // Un horario puede tener muchas solicitudes
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // Una solicitud puede tener un feedback
    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
