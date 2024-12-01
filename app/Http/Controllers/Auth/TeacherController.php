<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('auth.teacher.login');
    }

    public function login(Request $request)
    {
    
        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('teacher')->attempt($credentials)) {
            return redirect()->intended('/teacher');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegisterForm()
    {
        abort(404);
        return view('auth.teacher.register');
    }

    public function register(Request $request)
    {
        abort(404);
        $this->validator($request->all())->validate();

        $Teacher = $this->create($request->all());

        $Teacher->assignRole('teacher');

        Auth::guard('Teacher')->login($teacher);

        return redirect('/teacher');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:teachers'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        abort(404);
        return Teacher::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/teacher/login');
    }

    public function home(){
        return view('teacher.home');
    }
}
