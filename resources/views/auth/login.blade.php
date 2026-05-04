@extends('layouts.app')

@section('title', 'Login - LinkLearn')

@section('content')
<div style="width: 100%; max-width: 450px; padding: 2rem 0;">
    <div class="card-modern" style="padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--brand); letter-spacing: -0.03em;">Welcome Back</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Login to manage your tutoring center</p>
        </div>

        {{-- Alert for Errors --}}
        @if(session('error'))
            <div style="background: #FEE2E2; color: #B91C1C; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 600; text-align: center; border: 1px solid #FECACA;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #FEE2E2; color: #B91C1C; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 600; border: 1px solid #FECACA;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Google Login Button (Primary Action) --}}
        <a href="{{ route('google.login') }}" class="btn btn-outline" style="width: 100%; border-width: 2px; margin-bottom: 1.5rem; justify-content: center;">
            <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.16H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.84l3.66-2.75z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.16l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Continue with Google
        </a>

        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="flex-grow: 1; height: 1px; background: var(--border);"></div>
            <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">OR</span>
            <div style="flex-grow: 1; height: 1px; background: var(--border);"></div>
        </div>

        {{-- Manual Login Form --}}
        <form action="{{ route('login') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf
            <div>
                <label style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" name="email" placeholder="name@example.com" 
                       style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem;" required>
            </div>

            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand);">Password</label>
                    <a href="#" style="font-size: 0.75rem; color: var(--accent); font-weight: 700; text-decoration: none;">Forgot?</a>
                </div>
                <input type="password" name="password" placeholder="••••••••" 
                       style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem;" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; margin-top: 0.5rem;">Login Account</button>
        </form>

        <p style="text-align: center; margin-top: 2rem; font-size: 0.9rem; color: var(--text-muted); font-weight: 500;">
            Don't have an account? <button type="button" onclick="window.location.href='{{ route('register') }}'" style="background: none; border: none; color: var(--accent); font-weight: 700; text-decoration: none; cursor: pointer; font-size: 0.9rem; font-family: inherit; padding: 0;">Create one here</button>
        </p>
    </div>
</div>
@endsection
