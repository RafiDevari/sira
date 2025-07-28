<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    use HasFactory;
    public function project() {
        return $this->belongsTo(Project::class, 'id_project');
    }
    public function tasks() {
    return $this->hasMany(Task::class, 'sprint_id');

}

    protected $fillable = [
        'nama',
        'id_project',
        'waktu_mulai',
        'waktu_selesai',
        'status',
    ];
}
