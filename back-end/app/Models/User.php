<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'email', 'password' ,'api_token'];

    /**
     * @return HasMany
     */
    public function tasksLists(): HasMany
    {
        return $this->hasMany('App\Models\TasksList', 'creator_id');
    }

    /**
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany('App\Models\Task', 'creator_id');
    }

}
