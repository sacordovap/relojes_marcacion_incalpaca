<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="col-md-4">
            <div class="card bg-dark text-white shadow-lg">
                <div class="card-body text-center">
                    <img src="{{ asset('images/incalpaca.png') }}" alt="Logo" class="mb-3">

                    <form method="GET" action="{{ route('dashboard') }}" class="text-left" onsubmit="return validateForm()">
                        @csrf

                        <div class="form-group">
                            <label for="username">{{ __('Username') }}</label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                                name="username" value="{{ old('username') }}" required autofocus>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;

            if ((username === 'admin' && password === 'admin') || (username === 'admin1' && password === 'admin1')) {
                // Redirect to the dashboard if username and password match
                window.location.href = "{{ route('dashboard') }}";
                return false; // Prevent form submission
            } else {
                // Show an alert for incorrect username or password
                alert('Usuario o contraseña incorrecto');
                return false; // Prevent form submission
            }
        }
    </script>
@endsection
