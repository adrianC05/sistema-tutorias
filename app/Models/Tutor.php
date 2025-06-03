<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Tutor extends Model
{
    /** @use HasFactory<\Database\Factories\TutorFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = ['user_id', 'specialty'];

    // Un usuario puede tener muchos tutores
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un Tutor puede tener muchos Horarios
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
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
