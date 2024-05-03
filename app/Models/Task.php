<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tasks';

    protected $fillable = [
        'task_name',
        'date',
        'status',
        'user_id',
    ];


    public function User()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
