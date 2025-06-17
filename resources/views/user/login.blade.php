<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Sign Up</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS yang dipindah ke public/assets -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"> 

</head>
<body>
    <div class="container" id="main-container">
        {{-- Tampilkan Pesan Flash (Error/Success) --}}
        @if (Session::has('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        {{-- Tampilkan Kesalahan Validasi Laravel --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container" id="login-container">
            <div class="form-content">
                <h1>Log in</h1>
                <p>Belum punya akun? <a href="#signup" id="show-signup">Sign Up</a></p> {{-- href="#" digunakan karena JS akan menghandle toggle --}}
                
                <form id="login-form" action="{{ route('authentication') }}" method="POST">
                    @csrf {{-- Token CSRF Laravel --}}
                    <div class="form-group">
                        <label for="login-email">Email</label>
                        <input type="email" id="login-email" name="email" value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <div class="password-container">
                            <input type="password" id="login-password" name="password" required>
                            <button type="button" class="toggle-password" id="toggle-login-password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">Log in</button>
                </form>
            </div>
            <div class="form-side"></div>
        </div>
        
        <div class="form-container hidden" id="signup-container">
            <div class="form-side"></div>
            <div class="form-content">
                <h1>Sign Up</h1>
                <p>Sudah punya akun? <a href="#login" id="show-login">Log in</a></p> {{-- href="#" digunakan karena JS akan menghandle toggle --}}
                
                <form id="signup-form" action="{{ route('register.submit') }}" method="POST">
                    @csrf {{-- Token CSRF Laravel --}}
                    <div class="form-group">
                        <label for="signup-name">Nama Lengkap</label>
                        <input type="text" id="signup-name" name="name" value="{{ old('name') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="signup-email">Email</label>
                        <input type="email" id="signup-email" name="email" value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="signup-password">Password</label>
                        <input type="password" id="signup-password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="signup-confirm-password">Confirm Password</label>
                        <input type="password" id="signup-confirm-password" name="password_confirmation" required> {{-- name="password_confirmation" penting untuk validasi Laravel --}}
                    </div>
                    
                    <button type="submit" class="btn-submit">Sign Up</button>
                </form>
            </div>
        </div>
    </div>

    <!-- JS yang dipindah ke public/assets -->
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>