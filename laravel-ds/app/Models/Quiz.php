<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Quiz
 *
 * @property int $id
 * @property string $name
 * @property array $steps
 * @property array $schema_question
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\QuizAnswer[] $QuizAnswer
 * @property-read int|null $quiz_answer_count
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz query()
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereSchemaQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereSteps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
