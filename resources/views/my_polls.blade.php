@extends('layouts.app')

@section('content')
<div class="container ">
    <div class="row h-100 m-auto">
        <div class="m-auto d-table" style="width:480px;">
            <div class="card card-block d-table-cell align-middle">
                <div class="card-body poll h-100 col cbg-color">
                    <h1 class="question">Poll name</h1>
                    <hr>          
                    @foreach(json_decode($id) as $test)
                        <h2 class="answer"><a href="{{ route('poll', ['p' => $test->id]) }}">{{ $test->question }}</a></h2>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .cbg-color{
        background-color: rgb(245, 222, 93);
    }

    .poll::before {
        content: '';
        position: absolute;
        top: 0;
        left: 65px;
        bottom: 0;
        width: 8px;
        border-left: 2px solid #ca302c;
    }

    hr{
        position: absolute;
        padding: 0;
        margin: 0;
        left: 0;
        width:100%;
        border: 1px solid blue;
    }
    textarea{
        overflow-y: hidden;
        font-size:32;
    }

    .answer{
        margin-left: 25%;
        line-height: 38px;
    }

    .answer:empty::before {
        content: 'Enter an answer';
        display: block;
        color: #766327;
    }

    .answer::after{
        display: block;
        content: "";
        position: absolute;
        padding: 0;
        margin: 0;
        left: 0;
        width:100%;
        border: 1px solid blueviolet;
    }

    .question{
        margin-left: 15%;
        margin-top: 10%;
    }

    .question:empty::before {
        content: 'Type your question here';
        display: block;
        color: #766327;
    }
</style>