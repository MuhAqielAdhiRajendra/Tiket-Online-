<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    
    // Izin biar bisa diisi data banyak sekaligus (Mass Assignment)
    protected $guarded = ['id']; 

    // Relasi (Nanti bakal kepakai)
    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}