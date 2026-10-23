@extends('platform.layout')

@section('title', 'Perfect Solution | دخول')

@section('content')
    <h1>دخول Perfect Solution</h1>
    <p class="muted">What you want is here. من هنا تدير شركات السياحة: الموقع، الحسابات، والنطاق.</p>

    <form method="POST" action="{{ route('platform.login.store') }}">
        @csrf
        <label for="email">البريد</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email') <div class="error">{{ $message }}</div> @enderror

        <label for="password">كلمة المرور</label>
        <input id="password" type="password" name="password" required>

        <label style="font-weight:400">
            <input type="checkbox" name="remember" value="1" style="width:auto"> تذكرني
        </label>

        <div style="margin-top:18px">
            <button type="submit">دخول</button>
        </div>
    </form>
@endsection
