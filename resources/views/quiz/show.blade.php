@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>{{ $quiz->title }}</h1>
        <p>{{ $quiz->description }}</p>
        <form action="{{ route('quiz.submit', $quiz) }}" method="POST">
            @csrf
            @foreach($quiz->questions as $question)
                <div>
                    <h4>{{ $question->question_text }}</h4>
                    @foreach($question->options as $option)
                        <div>
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}">
                            <label>{{ $option->option_text }}</label>
                        </div>
                    @endforeach
                </div>
            @endforeach
            <button type="submit">Submit Quiz</button>
        </form>
    </div>
@endsection