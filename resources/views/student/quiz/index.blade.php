@extends('student.layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-black mb-4">Available Quizzes</h1>
    <div class="table-responsive">
        <table id="quizzes-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizzes as $quiz)
                    <tr>
                        <td>{{ $quiz->id }}</td>
                        <td>{{ $quiz->title }}</td>
                        <td>{{ $quiz->description }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary take-quiz-btn" data-id="{{ $quiz->id }}" data-bs-toggle="modal" data-bs-target="#takeQuizModal">Take Quiz</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 class="text-black mt-5">Latest Quiz Results</h2>
    <div class="table-responsive">
        <table id="quiz-results-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Quiz ID</th>
                    <th>Title</th>
                    <th>Marks</th>
                    <th>Total Marks</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizResults as $quiz)
                    <tr>
                        <td>{{$quiz->quiz_id}}</td>
                        <td>{{$quiz->quiz->title}}</td>
                        <td>{{$quiz->marks}}</td>
                        <td>{{$quiz->total_marks}}</td>
                        <td>{{$quiz->date}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Take Quiz Modal -->
<div class="modal fade" id="takeQuizModal" tabindex="-1" aria-labelledby="takeQuizModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="takeQuizModalLabel">Take Quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="takeQuizForm">
                    @csrf
                    <input type="hidden" id="quizId" name="quiz_id">
                    <div id="questionsContainer">
                        <!-- Questions will be loaded here via AJAX -->
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Submit Quiz</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
    <style>
        .text-white {
            color: white !important;
        }
    </style>
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
@endpush

@push('scripts')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#quizzes-table').DataTable({
                // DataTable options can be added here
            });

            $('#quiz-results-table').DataTable({
                // DataTable options can be added here
            });

            // Handle take quiz button click
            $(document).on('click', '.take-quiz-btn', function() {
                var quizId = $(this).data('id');
                $.ajax({
                    url: '/student/quiz/' + quizId,
                    method: 'GET',
                    success: function(response) {
                        var quiz = response.quiz;
                        $('#quizId').val(quiz.id);
                        $('#takeQuizModalLabel').text('Take Quiz: ' + quiz.title);
                        var questionsHtml = '';
                        quiz.questions.forEach(function(question, questionIndex) {
                            questionsHtml += `
                                <div class="question mb-3">
                                    <h5 class="text-white">Question ${questionIndex + 1}</h5>
                                    <label for="questionText" class="form-label">${question.question_text}</label>
                                    <div class="options mt-2">`;
                            question.options.forEach(function(option, optionIndex) {
                                questionsHtml += `
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answers[${question.id}]" value="${option.id}" id="option${option.id}">
                                        <label class="form-check-label text-white" for="option${option.id}">
                                            ${option.option_text}
                                        </label>
                                    </div>`;
                            });
                            questionsHtml += `
                                    </div>
                                </div>`;
                        });
                        $('#questionsContainer').html(questionsHtml);
                        $('#takeQuizModal').modal('show');
                    },
                    error: function(response) {
                        console.error('An error occurred while fetching the quiz data:', response);
                        alert('An error occurred while fetching the quiz data.');
                    }
                });
            });

            // Handle quiz form submission
            $('#takeQuizForm').submit(function(event) {
                event.preventDefault();
                var quizId = $('#quizId').val();
                var url = '/student/quiz/' + quizId + '/submit';
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            console.log('Quiz submitted successfully:', response);
                            location.reload(); // Refresh the page completely
                        } else {
                            console.error('An error occurred while submitting the quiz:', response);
                            alert('An error occurred while submitting the quiz.');
                        }
                    },
                    error: function(response) {
                        console.error('An error occurred while submitting the quiz:', response);
                        // alert('An error occurred while submitting the quiz.');
                    }
                });
            });
        });
    </script>
    <!-- END PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
@endpush