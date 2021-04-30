<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model {

  use HasFactory;

  protected $fillable = [
    'name',
    'steps',
    'schema_question',
  ];

  protected $casts = [
    'schema_question' => 'array',
    'steps' => 'array',
  ];

  public function QuizAnswer() {
    return $this->hasMany('App\Models\QuizAnswer');
  }
}
