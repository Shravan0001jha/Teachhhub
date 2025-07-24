<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('auth.student.login');
    }

    public function login(Request $request)
    {
    
        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('student')->attempt($credentials)) {
            return redirect()->intended('/student');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegisterForm()
    {
        // abort(404);
        return view('auth.student.register');
    }

    public function register(Request $request)
    {
        // abort(404);
        $this->validator($request->all())->validate();

        $Student = $this->create($request->all());

        $Student->assignRole('student');

        // Auth::guard('Student')->login($student);

        return redirect('/student');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        // abort(404);
        return Student::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'created_at' => now(),
            'created_by' => '7',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/student/login');
    }

    public function home(){
        return view('student.home');
    }
}
