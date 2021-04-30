<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizAnswersTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('quiz_answers', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->unsigned()->nullable()->constrained('users')->onDelete('cascade');
      $table->foreignId('quiz_id')->unsigned()->nullable()->constrained('quizzes')->onDelete('cascade');
      //$table->integer("quiz_id")->unsigned()->nullable();
      //$table->integer("user_id")->unsigned()->nullable();
      $table->text("steps")->nullable();
      $table->timestamps();

      //$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      //$table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('quiz_answers');
  }
}
