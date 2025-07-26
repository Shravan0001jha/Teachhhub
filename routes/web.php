<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SuperAdminController;
use App\Http\Controllers\Auth\AdminController as AuthAdminController;
use App\Http\Controllers\Auth\TeacherController as AuthTeacherController;
use App\Http\Controllers\Auth\StudentController as AuthStudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\StudyMaterialController;
use App\Http\Controllers\ZoomController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\QuizController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('login', [SuperAdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [SuperAdminController::class, 'login']);
    Route::get('register', [SuperAdminController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [SuperAdminController::class, 'register']);
    Route::post('logout', [SuperAdminController::class, 'logout'])->name('logout');

    Route::middleware('superadmin')->group(function () {
        Route::get('/', [SuperAdminController::class, 'home'])->name('home');
        Route::resource('admin',AdminController::class);
    });
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthAdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthAdminController::class, 'login']);
    Route::get('register', [AuthAdminController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthAdminController::class, 'register']);
    Route::post('logout', [AuthAdminController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [AuthAdminController::class, 'home'])->name('home');

        Route::prefix('batch')->name('batch.')->group(function () {
            Route::get('/zoomtest',[BatchController::class,'zoomtest']);
        });


        Route::resource('teacher',TeacherController::class);
        Route::resource('student',StudentController::class);
        Route::resource('batch',BatchController::class);

        // Add other routes that require admin authentication here
    });
});

Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::get('login', [AuthTeacherController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthTeacherController::class, 'login']);
    Route::get('register', [AuthTeacherController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthTeacherController::class, 'register']);
    Route::post('logout', [AuthTeacherController::class, 'logout'])->name('logout');

    // Routes that require teacher authentication
    Route::middleware('teacher')->group(function () {
        Route::get('/', [AuthTeacherController::class, 'home'])->name('home');
        // Add other routes that require teacher authentication here
        Route::resource('studyMaterial',StudyMaterialController::class);
        Route::resource('meeting',MeetingController::class);

        Route::get('quizzes', [QuizController::class, 'teacherIndex'])->name('quiz.index');
        Route::get('quiz/create', [QuizController::class, 'create'])->name('quiz.create');
        Route::post('quiz', [QuizController::class, 'store'])->name('quiz.store');
        Route::get('quiz/{id}/edit', [QuizController::class, 'edit'])->name('quiz.edit');
        Route::put('quiz/{quiz}', [QuizController::class, 'update'])->name('quiz.update');
        Route::delete('quiz/{quiz}', [QuizController::class, 'destroy'])->name('quiz.destroy');
        Route::post('quiz/{quiz}/activate', [QuizController::class, 'activate'])->name('quiz.activate');
        Route::post('quiz/{quiz}/deactivate', [QuizController::class, 'deactivate'])->name('quiz.deactivate');
        Route::get('quiz/{quiz}/submission/{student}', [QuizController::class, 'viewSubmission'])->name('quiz.viewSubmission');
    });
});

Route::prefix('student')->name('student.')->group(function () {
    Route::get('login', [AuthStudentController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthStudentController::class, 'login']);
    Route::get('register', [AuthStudentController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthStudentController::class, 'register']);
    Route::post('logout', [AuthStudentController::class, 'logout'])->name('logout');

    // Routes that require student authentication
    Route::middleware('student')->group(function () {
        Route::get('/', [AuthStudentController::class, 'home'])->name('home');
        Route::get('/get-meeting',[MeetingController::class, 'studentGetMeeting'])->name('get-meeting');
        Route::get('/show-meeting/{meeting}',[MeetingController::class, 'studentShowMeeting'])->name('show-meeting');
        // Add other routes that require student authentication here

        Route::get('quizzes', [QuizController::class, 'studentIndex'])->name('quiz.index');
        Route::get('quiz/{quiz}', [QuizController::class, 'show'])->name('quiz.show');
        Route::post('quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
        Route::get('quiz/{quiz}/results', [QuizController::class, 'results'])->name('quiz.results');
    });
});


Route::get('/zoom/redirect', [ZoomController::class, 'redirectToZoom'])->name('zoom.redirect');
Route::get('/zoom/callback', [ZoomController::class, 'handleZoomCallback'])->name('zoom.callback');
Route::get('/zoom/meetings', [ZoomController::class, 'createMeeting'])->name('zoom.meeting.create');
Route::get('/zoom/refreshAccessToken',[ZoomController::class, 'refreshAccessToken'])->name('zoom.refreshToken');
