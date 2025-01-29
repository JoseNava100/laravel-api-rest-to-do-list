<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'completed',
        'user_id',
    ];

    protected $table = 'tasks';

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

}
