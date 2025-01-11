@extends('teacher.layouts.app')

@section('content')
    <div class="container">
        <h1>My Quizzes</h1>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createQuizModal">
            Create Quiz
        </button>
        <div class="table-responsive">
            <table id="quizzes-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quizzes as $quiz)
                        <tr>
                            <td>{{ $quiz->id }}</td>
                            <td>{{ $quiz->title }}</td>
                            <td>{{ $quiz->description }}</td>
                            <td>{{ $quiz->created_at }}</td>
                            <td>
                            <a href="javascript:void(0);" class="btn btn-sm btn-warning edit-quiz-btn" data-id="{{ $quiz->id }}">Edit</a>
                                <form action="{{ route('teacher.quiz.destroy', $quiz->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Quiz Modal -->
    <div class="modal fade" id="createQuizModal" tabindex="-1" aria-labelledby="createQuizModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="createQuizModalLabel">Create Quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="quizForm">
                    @csrf
                    <input type="hidden" id="quizId" name="quiz_id">
                    <div class="mb-3">
                        <label for="quizTitle" class="form-label">Quiz Title</label>
                        <input type="text" class="form-control" id="quizTitle" name="title" placeholder="Enter quiz title" required>
                    </div>
                    <div class="mb-3">
                        <label for="quizDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="quizDescription" name="description" placeholder="Enter quiz description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="batchId" class="form-label">Batch</label>
                        <select class="form-control" id="batchId" name="batch_id" required>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="questionsContainer">
                        <div class="question mb-3">
                            <h5 class="text-white">Question 1</h5>
                            <label for="questionText" class="form-label">Question</label>
                            <input type="text" class="form-control" name="questions[0][question_text]" placeholder="Enter question text" required>
                            <div class="options mt-2">
                                <label for="optionText" class="form-label">Options</label>
                                <input type="text" class="form-control mb-2" name="questions[0][options][0][option_text]" placeholder="Option 1" required>
                                <input type="text" class="form-control mb-2" name="questions[0][options][1][option_text]" placeholder="Option 2" required>
                                <input type="text" class="form-control mb-2" name="questions[0][options][2][option_text]" placeholder="Option 3" required>
                                <input type="text" class="form-control mb-2" name="questions[0][options][3][option_text]" placeholder="Option 4" required>
                                <label for="correctOption" class="form-label mt-2">Correct Answer</label>
                                <select class="form-select" name="questions[0][correct_option]" required>
                                    <option value="" disabled selected>Select the correct answer</option>
                                    <option value="0">Option 1</option>
                                    <option value="1">Option 2</option>
                                    <option value="2">Option 3</option>
                                    <option value="3">Option 4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" id="addQuestionButton">Add Question</button>
                    <button type="submit" class="btn btn-primary">Submit Quiz</button>
                </form>
            </div>
        </div>
    </div>
</div>
    @include('teacher.quiz.edit')
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
            const table = $('#quizzes-table').DataTable({
                // DataTable options can be added here
            });

            let questionIndex = 1;

            $('#addQuestionButton').click(function() {
                const questionHtml = `
                    <div class="question mb-3">
                        <h5 class="text-white">Question ${questionIndex + 1}</h5>
                        <label for="questionText" class="form-label">Question</label>
                        <input type="text" class="form-control" name="questions[${questionIndex}][question_text]" placeholder="Enter question text" required>
                        <div class="options mt-2">
                            <label for="optionText" class="form-label">Options</label>
                            <input type="text" class="form-control mb-2" name="questions[${questionIndex}][options][0][option_text]" placeholder="Option 1" required>
                            <input type="text" class="form-control mb-2" name="questions[${questionIndex}][options][1][option_text]" placeholder="Option 2" required>
                            <input type="text" class="form-control mb-2" name="questions[${questionIndex}][options][2][option_text]" placeholder="Option 3" required>
                            <input type="text" class="form-control mb-2" name="questions[${questionIndex}][options][3][option_text]" placeholder="Option 4" required>
                            <label for="correctOption" class="form-label mt-2">Correct Answer</label>
                            <select class="form-select" name="questions[${questionIndex}][correct_option]" required>
                                <option value="" disabled selected>Select the correct answer</option>
                                <option value="0">Option 1</option>
                                <option value="1">Option 2</option>
                                <option value="2">Option 3</option>
                                <option value="3">Option 4</option>
                            </select>
                        </div>
                    </div>
                `;
                $('#questionsContainer').append(questionHtml);
                questionIndex++;
            });

            // Handle create/edit quiz form submission via AJAX
            $('#quizForm').submit(function(event) {
                event.preventDefault();
                const quizId = $('#quizId').val();
                const url = quizId ? '/quiz/' + quizId : '{{ route('teacher.quiz.store') }}';
                const method = quizId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            location.reload(); // Refresh the page
                        } else {
                            alert('An error occurred while saving the quiz.');
                        }
                    },
                    error: function(response) {
                        console.error('An error occurred while saving the quiz:', response);
                        alert('An error occurred while saving the quiz.');
                    }
                });
            });

            // Handle edit button click
            $(document).on('click', '.edit-quiz-btn', function() {
                var quizId = $(this).data('id');
                $.ajax({
                    url: 'quiz/' + quizId + '/edit',
                    method: 'GET',
                    success: function(response) {
                        var quiz = response.quiz;
                        $('#quizId').val(quiz.id);
                        $('#quizTitle').val(quiz.title);
                        $('#quizDescription').val(quiz.description);
                        $('#createQuizModalLabel').text('Edit Quiz');
                        $('#quizForm').attr('method', 'PUT');
                        console.log(quiz);
                        var questionsHtml = '';
                        quiz.questions.forEach(function(question, questionIndex) {
                            questionsHtml += `
                                <div class="question mb-3">
                                    <h5 class="text-white">Question ${questionIndex + 1}</h5>
                                    <label for="questionText" class="form-label">Question</label>
                                    <input type="text" class="form-control" name="questions[${questionIndex}][question_text]" value="${question.question_text}" placeholder="Enter question text" required>
                                    <div class="options mt-2">
                                        <label for="optionText" class="form-label">Options</label>`;
                            question.options.forEach(function(option, optionIndex) {
                                questionsHtml += `
                                    <input type="text" class="form-control mb-2" name="questions[${questionIndex}][options][${optionIndex}][option_text]" value="${option.option_text}" placeholder="Option ${optionIndex + 1}" required>`;
                            });
                            questionsHtml += `
                                        <label for="correctOption" class="form-label mt-2">Correct Answer</label>
                                        <select class="form-select" name="questions[${questionIndex}][correct_option]" required>
                                            <option value="" disabled>Select the correct answer</option>`;
                            question.options.forEach(function(option, optionIndex) {
                                questionsHtml += `
                                            <option value="${optionIndex}" ${option.is_correct ? 'selected' : ''}>Option ${optionIndex + 1}</option>`;
                            });
                            questionsHtml += `
                                        </select>
                                    </div>
                                </div>`;
                        });
                        $('#questionsContainer').html(questionsHtml);
                        $('#createQuizModal').modal('show');
                    },
                    error: function(response) {
                        console.error('An error occurred while fetching the quiz data:', response);
                        alert('An error occurred while fetching the quiz data.');
                    }
                });
            });

            // Reset the modal when it's closed
            $('#createQuizModal').on('hidden.bs.modal', function () {
                $('#quizForm')[0].reset();
                $('#quizId').val('');
                $('#createQuizModalLabel').text('Create Quiz');
                $('#questionsContainer').html(`
                    <div class="question mb-3">
                        <h5 class="text-white">Question 1</h5>
                        <label for="questionText" class="form-label">Question</label>
                        <input type="text" class="form-control" name="questions[0][question_text]" placeholder="Enter question text" required>
                        <div class="options mt-2">
                            <label for="optionText" class="form-label">Options</label>
                            <input type="text" class="form-control mb-2" name="questions[0][options][0][option_text]" placeholder="Option 1" required>
                            <input type="text" class="form-control mb-2" name="questions[0][options][1][option_text]" placeholder="Option 2" required>
                            <input type="text" class="form-control mb-2" name="questions[0][options][2][option_text]" placeholder="Option 3" required>
                            <input type="text" class="form-control mb-2" name="questions[0][options][3][option_text]" placeholder="Option 4" required>
                            <label for="correctOption" class="form-label mt-2">Correct Answer</label>
                            <select class="form-select" name="questions[0][correct_option]" required>
                                <option value="" disabled selected>Select the correct answer</option>
                                <option value="0">Option 1</option>
                                <option value="1">Option 2</option>
                                <option value="2">Option 3</option>
                                <option value="3">Option 4</option>
                            </select>
                        </div>
                    </div>
                `);
            });
        });
    </script>
    <!-- END PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
@endpush