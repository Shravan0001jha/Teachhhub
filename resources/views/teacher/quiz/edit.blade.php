
<!-- Create Quiz Modal -->
<div class="modal fade" id="createQuizModal" tabindex="-1" aria-labelledby="createQuizModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="createQuizModalLabel">Create Quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createQuizForm">
                    @csrf
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