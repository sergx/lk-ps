<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\QuizAnswer
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $quiz_id
 * @property array|null $steps
 * @property \datetime|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Quiz|null $Quiz
 * @property-read \App\Models\User|null $User
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAnswer whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAnswer whereSteps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAnswer whereUserId($value)
 * @mixin \Eloquent
 */
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
