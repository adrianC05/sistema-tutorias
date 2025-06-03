<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory, LogsActivity;
    protected $fillable = ['tutor_id', 'class_date', 'start_time', 'end_time'];

    // Un Tutor puede tener muchos Horarios
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    // Un horario puede tener muchas solicitudes
    public function students()
    {
        return $this->hasMany(Application::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
