<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status'];

    /**
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'creator_id');
    }

    /**
     * @return BelongsTo
     */
    public function tasksList(): BelongsTo
    {
        return $this->belongsTo('App\Models\TasksList', 'taskslist_id');
    }

}
