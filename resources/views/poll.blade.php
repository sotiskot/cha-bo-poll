@extends('layouts.app')

@section('content')
<div class="container">
    @if(Cookie::get('laravel_cookie_consent'))
        @if(isset($_COOKIE['has_voted_'.$_GET['p']]))
            <show-poll id="{{ $_GET['p'] }}" poll="{{ $poll }}" answers="{{ $answers }}" user="{{ Auth::user() }}"></show-poll>
        @else
            <vote-poll id="{{ $_GET['p'] }}" poll="{{ $poll }}" answers="{{ $answers }}"></vote-poll>
        @endif
    @else
        <div class="text-center">
            <h1 class="text-white">Plz allow cookies to be able to paricipate in the Poll</h1>
        </div>
    @endif
</div>
@endsection
