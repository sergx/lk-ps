<?php

namespace App\Http\Controllers;

use App\Models\QuizAnswer;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizAnswerController extends Controller
{

  public function index() {
    $items = QuizAnswer::with(['Quiz:id,name', 'User'])->get();
    return $items;
  }

  public function show(Request $request) {
    $item = Quiz::with(['QuizAnswer', 'QuizAnswer.User'])->where('id', $request->id)->first();
    return $item;
  }

  public function save(Request $request){
    $item = QuizAnswer::create($request->all());
    $item->user_id = Auth::user()->id;
    $item->save();
    return [
      'notify' => ['type' => 'success', 'text' => 'Ответы сохранены. Спасибо!']
    ];
  }

  public function destroy(Request $request) {
    $item = QuizAnswer::find($request->id);
    $item->delete();
    return [
      'notify' => ['text' => 'Ответ на опрос удален', 'duration' => 10000]
    ];
  }
}
