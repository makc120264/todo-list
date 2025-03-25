@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Login</h2>

        <!-- Форма авторизации -->
        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <!-- Электронная почта -->
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Пароль -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Кнопка отправки формы -->
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <!-- Сообщение об ошибке -->
        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif
    </div>
@endsection
