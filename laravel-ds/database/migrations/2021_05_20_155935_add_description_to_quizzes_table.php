<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToQuizzesTable extends Migration {

  public function up() {
    Schema::table('quizzes', function (Blueprint $table) {
      $table->text("description");
    });
  }

  public function down() {
    Schema::table('quizzes', function (Blueprint $table) {
      $table->dropColumn("description");
    });
  }
}
