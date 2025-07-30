<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    public function sprint() {
        return $this->belongsTo(Sprint::class, 'sprint_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
    
    protected $fillable = ['name', 'sprint_id', 'status', 'user_id', 'type', 'description', 'waktu_mulai', 'waktu_selesai'];


}
