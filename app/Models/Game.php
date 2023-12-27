<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'score', 'is_ended'];

    protected $hidden = ['is_ended'];

    public function user () {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}