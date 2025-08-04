<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;

    public function quiz() : BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers() : HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
