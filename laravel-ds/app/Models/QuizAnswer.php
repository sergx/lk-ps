<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model {
  use HasFactory;

  protected $fillable = [
    'user_id',
    'quiz_id',
    'steps',
  ];

  protected $casts = [
    'steps' => 'array',
    'created_at' => 'datetime:H:i d.m.Y',
  ];

  public function User(){
    return $this->belongsTo('App\Models\User');
  }

  public function Quiz(){
    return $this->belongsTo('App\Models\Quiz');
  }

}
