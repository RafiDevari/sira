<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sprint;



class Project extends Model
{
    use HasFactory;
    public function lead() {
        return $this->belongsTo(User::class, 'id_lead');
    }

    public function application() {
        return $this->belongsTo(Application::class, 'id_aplikasi');
    }
    protected $fillable = [
        'nama',
        'key',
        'deskripsi',
        'id_lead',
        'id_aplikasi',
        'waktu_mulai',
        'waktu_selesai',
        'tipe',
    ];
    public function sprints()
    {
        return $this->hasMany(Sprint::class, 'id_project');
    }



}
