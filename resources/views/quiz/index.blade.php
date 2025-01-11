@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Quizzes</h1>
        <ul>
            @foreach($quizzes as $quiz)
                <li><a href="{{ route('quiz.show', $quiz) }}">{{ $quiz->title }}</a></li>
            @endforeach
        </ul>
    </div>
@endsection