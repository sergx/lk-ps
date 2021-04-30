@extends('layouts.app')

@section('content')
<div id="app">
  <router-view></router-view> {{-- Не важно как называется - все равно выводиться App --}}
</div>
@endsection
