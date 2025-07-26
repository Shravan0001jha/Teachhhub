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
                                @if($quiz->status === 'ACTIVE')
                                <form action="{{ route('teacher.quiz.deactivate', $quiz->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-sm btn-warning">Deactivate</button>
                                </form>
                                @else
                                <form action="{{ route('teacher.quiz.activate', $quiz->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-sm btn-success">Activate</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Quiz Submissions Section -->
        <div class="mt-5">
            <h2>Quiz Submissions</h2>
            <div class="table-responsive">
                <table id="submissions-table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Quiz Name</th>
                            <th>Student Name</th>
                            <th>Score</th>
                            <th>Date Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 
                        TODO: Replace with dynamic data
                        Expected controller data:
                        $quizResults = QuizResult::where('teacher_id', auth()->user()->id)
                            ->with(['quiz', 'student'])
                            ->orderBy('date', 'desc')
                            ->get();
                        
                        Loop would be:
                        @foreach($quizResults as $result)
                            <tr>
                                <td>{{ $result->quiz->title }}</td>
                                <td>{{ $result->student->name }}</td>
                                <td>{{ $result->marks }}/{{ $result->total_marks }}</td>
                                <td>{{ $result->date }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info view-submission-btn" 
                                            data-quiz-id="{{ $result->quiz_id }}"
                                            data-student-id="{{ $result->student_id }}"
                                            data-quiz-title="{{ $result->quiz->title }}" 
                                            data-student-name="{{ $result->student->name }}"
                                            data-score="{{ $result->marks }}/{{ $result->total_marks }}">
                                        View Test
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        -->
                        
                        <!-- Hardcoded sample data for demonstration -->
                        <tr>
                            <td>Mathematics Quiz 1</td>
                            <td>John Doe</td>
                            <td>8/10</td>
                            <td>2024-07-25 14:30:00</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info view-submission-btn" 
                                        data-quiz-title="Mathematics Quiz 1" 
                                        data-student-name="John Doe"
                                        data-score="8/10"
                                        data-submission='[
                                            {
                                                "question": "What is 2 + 2?",
                                                "options": ["2", "3", "4", "5"],
                                                "student_answer": "4",
                                                "correct_answer": "4",
                                                "is_correct": true
                                            },
                                            {
                                                "question": "What is 5 × 6?",
                                                "options": ["25", "30", "35", "40"],
                                                "student_answer": "35",
                                                "correct_answer": "30",
                                                "is_correct": false
                                            }
                                        ]'>
                                    View Test
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>Science Quiz 1</td>
                            <td>Jane Smith</td>
                            <td>9/10</td>
                            <td>2024-07-24 10:15:00</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info view-submission-btn" 
                                        data-quiz-title="Science Quiz 1" 
                                        data-student-name="Jane Smith"
                                        data-score="9/10"
                                        data-submission='[
                                            {
                                                "question": "What is the chemical symbol for water?",
                                                "options": ["H2O", "CO2", "NaCl", "O2"],
                                                "student_answer": "H2O",
                                                "correct_answer": "H2O",
                                                "is_correct": true
                                            },
                                            {
                                                "question": "How many planets are in our solar system?",
                                                "options": ["7", "8", "9", "10"],
                                                "student_answer": "8",
                                                "correct_answer": "8",
                                                "is_correct": true
                                            }
                                        ]'>
                                    View Test
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>Mathematics Quiz 1</td>
                            <td>Bob Wilson</td>
                            <td>6/10</td>
                            <td>2024-07-23 16:45:00</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info view-submission-btn" 
                                        data-quiz-title="Mathematics Quiz 1" 
                                        data-student-name="Bob Wilson"
                                        data-score="6/10"
                                        data-submission='[
                                            {
                                                "question": "What is 2 + 2?",
                                                "options": ["2", "3", "4", "5"],
                                                "student_answer": "4",
                                                "correct_answer": "4",
                                                "is_correct": true
                                            },
                                            {
                                                "question": "What is 5 × 6?",
                                                "options": ["25", "30", "35", "40"],
                                                "student_answer": "25",
                                                "correct_answer": "30",
                                                "is_correct": false
                                            }
                                        ]'>
                                    View Test
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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

    <!-- View Quiz Submission Modal -->
    <div class="modal fade" id="viewSubmissionModal" tabindex="-1" aria-labelledby="viewSubmissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="viewSubmissionModalLabel">Quiz Submission Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6 class="text-white">Quiz: <span id="modal-quiz-title"></span></h6>
                        <h6 class="text-white">Student: <span id="modal-student-name"></span></h6>
                        <h6 class="text-white">Score: <span id="modal-score"></span></h6>
                    </div>
                    <div id="questions-container">
                        <!-- Questions will be populated here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            
            const submissionsTable = $('#submissions-table').DataTable({
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

            // Handle view submission button click
            $(document).on('click', '.view-submission-btn', function() {
                var quizTitle = $(this).data('quiz-title');
                var studentName = $(this).data('student-name');
                var score = $(this).data('score');
                var submissionData = $(this).data('submission');
                
                /*
                TODO: Replace hardcoded data with AJAX call
                For dynamic implementation, use:
                
                var quizId = $(this).data('quiz-id');
                var studentId = $(this).data('student-id');
                
                $.ajax({
                    url: '/teacher/quiz/' + quizId + '/submission/' + studentId,
                    method: 'GET',
                    success: function(response) {
                        var submissionData = response.submission_details;
                        // Then use the same modal population logic below
                    },
                    error: function() {
                        alert('Error loading submission details');
                    }
                });
                */
                
                // Populate modal header info
                $('#modal-quiz-title').text(quizTitle);
                $('#modal-student-name').text(studentName);
                $('#modal-score').text(score);
                
                // Clear previous questions
                $('#questions-container').empty();
                
                // Populate questions
                submissionData.forEach(function(item, index) {
                    var questionHtml = `
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0 text-white">Question ${index + 1}: ${item.question}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-white">Options:</h6>
                                        <ul class="list-group list-group-flush">`;
                                        
                    item.options.forEach(function(option, optionIndex) {
                        var isCorrect = option === item.correct_answer;
                        var isSelected = option === item.student_answer;
                        var badgeClass = '';
                        var iconClass = '';
                        
                        if (isCorrect && isSelected) {
                            badgeClass = 'bg-success';
                            iconClass = 'fa fa-check';
                        } else if (isCorrect) {
                            badgeClass = 'bg-success';
                            iconClass = 'fa fa-check';
                        } else if (isSelected) {
                            badgeClass = 'bg-danger';
                            iconClass = 'fa fa-times';
                        } else {
                            badgeClass = 'bg-light text-dark';
                        }
                        
                        questionHtml += `
                            <li class="list-group-item d-flex justify-content-between align-items-center ${isSelected ? 'border-primary' : ''}">
                                ${option}
                                ${(isCorrect || isSelected) ? `<span class="badge ${badgeClass}"><i class="${iconClass}"></i></span>` : ''}
                            </li>`;
                    });
                    
                    questionHtml += `
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-white">Student's Answer:</h6>
                                        <p class="text-white">${item.student_answer}</p>
                                        <h6 class="text-white">Correct Answer:</h6>
                                        <p class="text-white">${item.correct_answer}</p>
                                        <h6 class="text-white">Result:</h6>
                                        <span class="badge ${item.is_correct ? 'bg-success' : 'bg-danger'}">
                                            ${item.is_correct ? 'Correct' : 'Incorrect'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                        
                    $('#questions-container').append(questionHtml);
                });
                
                // Show the modal
                $('#viewSubmissionModal').modal('show');
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