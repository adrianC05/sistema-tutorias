<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory;
    protected $fillable = ['tutor_id', 'class_date', 'start_time', 'end_time'];

    // Un Tutor puede tener muchos Horarios
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

}
