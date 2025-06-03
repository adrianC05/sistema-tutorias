<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'carrier'];
    // Un usuario puede tener muchos estudiantes
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
