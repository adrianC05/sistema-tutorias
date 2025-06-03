<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    /** @use HasFactory<\Database\Factories\FeedbackFactory> */
    use HasFactory;
    protected $fillable = ['application_id', 'tutor_id', 'rating', 'comment'];

    // Una solicitud puede tener un feedback
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
