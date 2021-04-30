<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class appendAuthDataToEachResponce {

  public function share(Request $request) {
    $permissions = [];
    $user = Auth::user() ?: [];
    if($user){
      $permissions = Auth::user()->getAllPermissions()->pluck("name");
    }
    return [
      // 'errors' => function () use ($request) {
      //   return $this->resolveValidationErrors($request);
      // },
      'auth' => [
        'user' => $user,
        'permissions' => $permissions
      ]
    ];
  }
  // // Возможно иногда может потребоваться перезаписать входной параметр data в axios
  // const axiosInstance = axios.create({
  //   baseURL: "http://127.0.0.1:8000/",
  //   headers: {
  //     "Content-Type": "application/json"
  //   },
  //   transformResponse: axios.defaults.transformResponse.concat(data => {
  //     return data.props;
  //   })
  // });
  public function handle(Request $request, Closure $next) {
    //return json_encode(['yoyo']);

    $response = $next($request);
    $content = json_decode($response->content(), true);

    //Check if the response is JSON
    if (json_last_error() == JSON_ERROR_NONE) {
      $response->setContent(json_encode(
          array_merge(
            ['props' => $content],
            $this->share($request)
        )
      ));
    }
    return $response;
  }

  // public function resolveValidationErrors(Request $request) {
  //   if (!$request->session()->has('errors')) {
  //     return (object) [];
  //   }

  //   return (object) collect($request->session()->get('errors')->getBags())->map(function ($bag) {
  //     return (object) collect($bag->messages())->map(function ($errors) {
  //       return $errors[0];
  //     })->toArray();
  //   })->pipe(function ($bags) use ($request) {
  //     if ($bags->has('default') && $request->header('x-inertia-error-bag')) {
  //       return [$request->header('x-inertia-error-bag') => $bags->get('default')];
  //     } elseif ($bags->has('default')) {
  //       return $bags->get('default');
  //     } else {
  //       return $bags->toArray();
  //     }
  //   });
  // }
}
