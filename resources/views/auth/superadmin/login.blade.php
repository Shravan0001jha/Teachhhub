@extends('layouts.auth')

@section('content')
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('SuperAdmin Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<div class="container mx-auto align-self-center">
   
    <div class="row">

        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
            <div class="card mt-3 mb-3">
                <div class="card-body">
                    <form action="{{route('superadmin.login')}}" method="post" id="loginForm">
                        <div class="row">
                            @csrf
                            <div class="col-md-12 mb-3">
                                
                                <h2>Sign In</h2>
                                <p>Enter your email and password to login</p>
                                
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{old('email')}}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-4">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" value="{{old('password')}}">
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-secondary w-100">SIGN IN</button>
                                </div>
                            </div>
                            

                            <div class="col-12">
                                <div class="text-center">
                                    <p class="mb-0">Dont't have an account ? <a href="{{route('superadmin.register')}}" class="text-warning">Sign Up</a></p>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
    
</div>
@endsection
@push('styles')
<style>
    label.error {
        color: red;
        font-size: 0.8rem; /* Optional: Adjust font size */
    }
</style>
@endpush
@push('scripts')
<script>
    //  document.addEventListener('DOMContentLoaded', function() {
    //     document.querySelector('.btn').addEventListener('click', function() {
    //         danger_notification('test');
    //     });
    // });
    $(document).ready(function() {
        $('#loginForm').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                }
            },
            messages: {
                email: {
                    required: "Please enter your email address",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Please enter your password",
                    minlength: "Your password must be at least 6 characters long"
                }
            },
            errorElement: 'label', // Wrap error messages in <label>
            errorPlacement: function(error, element) {
                error.addClass('error-message');
                error.insertAfter(element); // Display error message after the invalid input
            },
            // highlight: function(element) {
            //     $(element).addClass('is-invalid'); // Optional: Add Bootstrap's is-invalid class for visual feedback
            // },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid'); // Optional: Remove Bootstrap's is-invalid class
            },
            submitHandler: function(form) {
                form.submit(); // Submit the form
            }
        });
    });
</script>    
@endpush