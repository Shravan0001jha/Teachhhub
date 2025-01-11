
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Quiz</h1>
        <form action="{{ route('teacher.quiz.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <div id="questions">
                <div class="question">
                    <div class="form-group">
                        <label for="question_text">Question</label>
                        <input type="text" name="questions[0][question_text]" class="form-control" required>
                    </div>
                    <div class="options">
                        <div class="form-group">
                            <label for="option_text">Option</label>
                            <input type="text" name="questions[0][options][0][option_text]" class="form-control" required>
                            <input type="checkbox" name="questions[0][options][0][is_correct]"> Correct
                        </div>
                    </div>
                    <button type="button" class="add-option">Add Option</button>
                </div>
            </div>
            <button type="button" id="add-question">Add Question</button>
            <button type="submit" class="btn btn-primary">Create Quiz</button>
        </form>
    </div>

    <script>
        document.getElementById('add-question').addEventListener('click', function() {
            const questionIndex = document.querySelectorAll('.question').length;
            const questionTemplate = `
                <div class="question">
                    <div class="form-group">
                        <label for="question_text">Question</label>
                        <input type="text" name="questions[${questionIndex}][question_text]" class="form-control" required>
                    </div>
                    <div class="options">
                        <div class="form-group">
                            <label for="option_text">Option</label>
                            <input type="text" name="questions[${questionIndex}][options][0][option_text]" class="form-control" required>
                            <input type="checkbox" name="questions[${questionIndex}][options][0][is_correct]"> Correct
                        </div>
                    </div>
                    <button type="button" class="add-option">Add Option</button>
                </div>
            `;
            document.getElementById('questions').insertAdjacentHTML('beforeend', questionTemplate);
        });

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('add-option')) {
                const questionElement = event.target.closest('.question');
                const optionIndex = questionElement.querySelectorAll('.options .form-group').length;
                const questionIndex = Array.from(document.querySelectorAll('.question')).indexOf(questionElement);
                const optionTemplate = `
                    <div class="form-group">
                        <label for="option_text">Option</label>
                        <input type="text" name="questions[${questionIndex}][options][${optionIndex}][option_text]" class="form-control" required>
                        <input type="checkbox" name="questions[${questionIndex}][options][${optionIndex}][is_correct]"> Correct
                    </div>
                `;
                questionElement.querySelector('.options').insertAdjacentHTML('beforeend', optionTemplate);
            }
        });
    </script>
@endsection