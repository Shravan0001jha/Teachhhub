<?php
namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\Response;
use App\Models\QuizResult;
use App\Models\Student;
use App\Models\Batch;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // Method for teachers to view their quizzes
    public function teacherIndex()
    {
        $quizzes = Quiz::where('teacher_id', auth()->guard('teacher')->user()->id)->get();
        $batches = Batch::all();
        return view('teacher.quiz.index', compact('quizzes', 'batches'));
    }

    // Method to show the form to create a new quiz
    public function create()
    {
        return view('quiz.teacher.create');
    }

    // Method to store a new quiz
    public function store(Request $request)
    {
        print_r("Store method called\n");
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'batch_id' => 'required|exists:batches,id',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.options' => 'required|array',
            'questions.*.options.*.option_text' => 'required|string',
            'questions.*.correct_option' => 'required|integer|min:0|max:3',
        ]);

        print_r("Validation passed\n");
        print_r("Auth ID: ");
        print_r(auth()->guard('teacher')->user()->id);

        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'batch_id' => $request->batch_id,
            'teacher_id' =>auth()->guard('teacher')->user()->id,
        ]);

        print_r("Quiz created: " . $quiz->id . "\n");

        foreach ($request->questions as $questionData) {
            print_r("Processing question: " . $questionData['question_text'] . "\n");
            $question = $quiz->questions()->create([
                'question_text' => $questionData['question_text'],
            ]);

            print_r("Question created: " . $question->id . "\n");
            // print_r("Question Data" . $request->questions . "\n");
            foreach ($questionData['options'] as $index => $optionText) {
                // print_r("Processing option: " . print_r($optionText, true) . "\n");
                $question->options()->create([
                    'option_text' => $optionText['option_text'],
                    'is_correct' => $index == $questionData['correct_option'],
                ]);
                print_r("Option created\n");
            }
        }

        print_r("All questions and options processed\n");
        return response()->json(['quiz' => $quiz], 201);
    }

    // Method to show the form to edit a quiz
    public function edit($id)
    {
        $quiz = Quiz::with('questions.options')->findOrFail($id);
        $quizData = [
            'id' => $quiz->id,
            'title' => $quiz->title,
            'description' => $quiz->description,
            'questions' => $quiz->questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'options' => $question->options->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'option_text' => $option->option_text,
                            'is_correct' => $option->is_correct,
                        ];
                    }),
                    'correct_option' => $question->options->search(function ($option) {
                        return $option->is_correct;
                    }),
                ];
            }),
        ];
        return response()->json(['quiz' => $quizData]);
    }

    // Method to update a quiz
    public function update(Request $request, Quiz $quiz)
    {
        print_r("Update method called\n");
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'batch_id' => 'required|exists:batches,id',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.options' => 'required|array',
            'questions.*.options.*.option_text' => 'required|string',
            'questions.*.correct_option' => 'required|integer|min:0|max:3',
        ]);

        print_r("Validation passed\n");

        $quiz->update([
            'title' => $request->title,
            'batch_id' => $request->batch_id,
            'description' => $request->description,
        ]);

        print_r("Quiz updated: " . $quiz->id . "\n");

        $quiz->questions()->delete();
        print_r("Existing questions deleted\n");

        foreach ($request->questions as $questionData) {
            print_r("Processing question: " . $questionData['question_text'] . "\n");
            $question = $quiz->questions()->create([
                'question_text' => $questionData['question_text'],
            ]);

            print_r("Question created: " . $question->id . "\n");

            foreach ($questionData['options'] as $index => $optionText) {
                print_r("Processing option: " . $optionText . "\n");
                $question->options()->create([
                    'option_text' => $optionText,
                    'is_correct' => $index == $questionData['correct_option'],
                ]);
                print_r("Option created\n");
            }
        }

        print_r("All questions and options processed\n");

        return redirect()->route('quiz.index')->with('success', 'Quiz updated successfully!');
    }

    

    // Method to delete a quiz
    public function destroy(Quiz $quiz)
    {
        print_r("Destroy method called\n");
        $quiz->delete();
        print_r("Quiz deleted: " . $quiz->id . "\n");
        //return response()->json(['success' => true]);
        return redirect()->route('teacher.quiz.index')->with('success', 'Quiz deleted successfully!');
    }

    // Method for students to view available quizzes
    public function studentIndex()
    {
        
        $studentId = auth()->guard('student')->user()->id;
        $student = Student::findOrFail($studentId);
        $batchId = $student->batch_id;
        $quizzes = Quiz::where('batch_id', $batchId)->get();
        $quizResults = QuizResult::where('student_id', $studentId)
            ->orderBy('date', 'desc')
            ->get();
        return view('student.quiz.index', compact('quizzes', 'studentId', 'quizResults'));
    }

    // Method for students to view a specific quiz
    public function show($id)
    {
        $quiz = Quiz::with('questions.options')->findOrFail($id);
        $quizData = [
            'id' => $quiz->id,
            'title' => $quiz->title,
            'description' => $quiz->description,
            'questions' => $quiz->questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'options' => $question->options->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'option_text' => $option->option_text,
                            'is_correct' => $option->is_correct,
                        ];
                    }),
                    'correct_option' => $question->options->search(function ($option) {
                        return $option->is_correct;
                    }),
                ];
            }),
        ];
        return response()->json(['quiz' => $quizData]);
    }

    // Method for students to submit quiz answers
    public function submit(Request $request, Quiz $quiz)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $answers = $request->input('answers');
        $totalMarks = 0;
        $obtainedMarks = 0;

        foreach ($quiz->questions as $question) {
            $totalMarks++;
            if (isset($answers[$question->id])) {
                $selectedOption = $answers[$question->id];
                $correctOption = $question->options->where('is_correct', true)->first();
                if ($correctOption && $correctOption->id == $selectedOption) {
                    $obtainedMarks++;
                }
            }
        }

        // Store the result in the quiz_results table
        QuizResult::create([
            'quiz_id' => $quiz->id,
            'student_id' => auth()->guard('student')->user()->id,
            'teacher_id' => $quiz->teacher_id,
            'marks' => $obtainedMarks,
            'total_marks' => $totalMarks,
            'date' => now(),
        ]);

        print_r("Quiz submitted\n");

        return redirect()->route('quiz.index')->with('success', 'Quiz submitted successfully!');
    }

    // Method for students to view quiz results
    public function results(Quiz $quiz)
    {
        $responses = Response::where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->get();

        return view('student.quiz.results', compact('quiz', 'responses'));
    }
}

// use App\Http\Controllers\QuizController;

// // Teacher Routes
// Route::middleware('teacher')->prefix('teacher')->name('teacher.')->group(function () {
//     Route::get('quizzes', [QuizController::class, 'teacherIndex'])->name('quiz.index');
//     Route::get('quiz/create', [QuizController::class, 'create'])->name('quiz.create');
//     Route::post('quiz', [QuizController::class, 'store'])->name('quiz.store');
// });

// // Student Routes
// Route::middleware('student')->prefix('student')->name('student.')->group(function () {
//     Route::get('quizzes', [QuizController::class, 'studentIndex'])->name('quiz.index');
//     Route::get('quiz/{quiz}', [QuizController::class, 'show'])->name('quiz.show');
//     Route::post('quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
// });