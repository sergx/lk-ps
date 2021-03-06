<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller {
  /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

  use RegistersUsers;

  public function showRegistrationForm() {
    if(!env('PIONEER_REGISTER_OPEN')){
      return redirect($this->redirectTo);
    }
    return view('auth.register');
  }

  /**
   * Where to redirect users after registration.
   *
   * @var string
   */
  protected $redirectTo = RouteServiceProvider::HOME;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct() {
    $this->middleware('guest');
  }

  /**
   * Get a validator for an incoming registration request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  public function validator(array $data) {
    return Validator::make($data, [
      'name' => ['required', 'string', 'max:191'],
      'child' => ['required', 'string', 'max:191'],
      'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
      'password' => ['required', 'string', 'min:5', 'confirmed'],
    ]);
  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array  $data
   * @return \App\Models\User
   */
  public function create(array $data) {
    return User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'child' => $data['child'],
      'phone' => $data['phone'],
      'password' => Hash::make($data['password']),
    ]);
  }
}
