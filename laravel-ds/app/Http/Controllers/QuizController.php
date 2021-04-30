<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
  // TODO permissions

  public function index(){
    $qb = Quiz::query();
    if (!Auth::user()->can('admin only')) {
      $user_id = Auth::user()->id;
      $qb->whereDoesntHave('QuizAnswer', function ($query) use ($user_id) {
        $query->where('user_id', $user_id);
      });
    }
    $quizzes = $qb->get();
    return $quizzes;
  }

  public function edit(Request $request){
    $quiz = Quiz::with(['QuizAnswer'])->find($request->id);
    return $quiz;
  }
  public function show(Request $request){
    $result = ['quiz' => []];
    $qb = Quiz::query();
    $qb->where('id', $request->id);
    if(!Auth::user()->can('admin only')){
      $user_id = Auth::user()->id;
      $qb->whereDoesntHave('QuizAnswer', function ($query) use ($user_id) {
        $query->where('user_id', $user_id);
      });
    }
    $items = $qb->get();
    if(count($items)){
      $result['quiz'] = $items->first();
    }else{
      $result['notify'] = ['text' => 'Вы уже проходили этот опрос. Ваши ответы учтены.', 'duration' => 10000, 'type' => 'warn'];
      $result['redirect'] = ['to' => ['name' => 'quiz.index'] ];
    }
    //$quiz = Quiz::find($request->id);
    return $result;
  }

  public function save(Request $request){
    if($request->id){
      $item = Quiz::find($request->id);
      $item->update($request->all());
      $text = "Опросник обновлен!";
    }else{
      $item = Quiz::create($request->all());
      $text = "Новый опросник создан!";
    }
    return [
      'item' => $item,
      'notify' => ['type' => 'success', 'text' => $text]
    ];
  }

  public function destroy(Request $request){
    $item = Quiz::find($request->id);
    $name = $item->name;
    if($item->delete()){
      return ['notify' => ['type' => 'success', 'text' => "Опрос <strong>" . $name . "</strong>(ID " . $request->id . ") удален!"]];
    }else{
      return ['notify' => ['type' => 'error', 'text' => "Что-то пошло не так.."]];
    }


  }

  public function fileUpload(Request $request){
    // Проверка, хотя можно обойтись и без нее
    $this->validate($request, [
      'image' => 'image|nullable',
    ]);

    // Имя и расширение файла
    $filenameWithExt = $request->file('image')->getClientOriginalName();
    // Только оригинальное имя файла
    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    // Расширение
    $extention = $request->file('image')->getClientOriginalExtension();
    // Путь для сохранения
    $fileNameToStore = "image/" . $filename . "_" . time() . "." . $extention;
    // Сохраняем файл
    $request->file('image')->storeAs('public', $fileNameToStore);

    // При выводе файла на странице нудно будет прибавить в начале "storage/"
    $fileNameToStore = "/lk/storage/". $fileNameToStore;
    return response()->json(["url" => $fileNameToStore]);
  }
}
