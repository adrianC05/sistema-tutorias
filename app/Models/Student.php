<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = ['user_id', 'carrier'];
    // Un usuario puede tener muchos estudiantes
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un estudiante puede tener muchas solicitudes
    public function student()
    {
        return $this->hasMany(Application::class);
    }

    public function getFullNameAttribute()
    {
        return $this->user
            ? $this->user->name . ' ' . $this->user->last_name
            : '(Sin usuario)';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
