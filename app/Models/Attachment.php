<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;
    public function task() {
        return $this->belongsTo(Task::class, 'task_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    protected $fillable = [
        'task_id',
        'user_id',
        'comment',
        'waktu_mulai',
        'waktu_selesai',
    ];
}
