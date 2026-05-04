@extends('layouts.app')

@section('title', 'Classrooms - LinkLearn')

@section('content')
<div style="width: 100%; display: flex; flex-direction: column; align-items: center;">
    @auth
        <div style="width: 100%; max-width: 1100px;">
            <div style="margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <h1 style="font-size: 2.25rem; font-weight: 800; letter-spacing: -0.04em; color: var(--brand); margin-bottom: 0.5rem;">
                        Active Classrooms
                    </h1>
                    <p style="color: var(--text-muted); font-weight: 600;">Your current educational organizations.</p>
                </div>
                <a href="/register-organization" class="btn btn-accent" style="box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3);">+ Create New</a>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
                @php
                    $organizations = \App\Models\Organization::where('user_id', auth()->id())
                                                            ->where('status', 'active')
                                                            ->get();
                @endphp
                @foreach($organizations as $org)
                <div style="background: #fff; border: 1px solid var(--border); border-radius: 1.25rem; overflow: hidden; transition: 0.3s; display: flex; flex-direction: column;" onmouseover="this.style.transform='translateY(-6px)'; this.style.borderColor='var(--accent)'; this.style.boxShadow='0 20px 25px -5px rgba(15, 23, 42, 0.05)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                    <div style="height: 140px; position: relative; border-bottom: 4px solid var(--accent); background: {{ $org->cover_photo ? 'url(/storage/'.$org->cover_photo.')' : 'var(--brand)' }}; background-size: cover; background-position: center;">
                        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(15,23,42,0.2), rgba(15,23,42,0.7)); padding: 1.5rem;">
                            <h3 style="color: #fff; font-size: 1.25rem; font-weight: 800; letter-spacing: -0.01em; margin-right: 2rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $org->name }}</h3>
                            <p style="color: var(--accent); font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">Active</p>
                        </div>
                        
                        <form action="{{ route('org.archive', $org->slug) }}" method="POST" style="position: absolute; top: 1rem; right: 1rem;" onsubmit="return confirm('Are you sure you want to archived this classroom?')">
                            @csrf
                            <button type="submit" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); border: none; width: 2.25rem; height: 2.25rem; border-radius: 0.75rem; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;">
                                <svg style="width: 1.2rem; height: 1.2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            </button>
                        </form>

                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($org->name) . '&background=f59e0b&color=0f172a' }}" style="position: absolute; bottom: -24px; right: 20px; width: 64px; height: 64px; border-radius: 50%; border: 4px solid #fff; box-shadow: var(--shadow); object-fit: cover;">
                    </div>
                    
                    <div style="padding: 2.5rem 1.5rem 1.5rem; flex-grow: 1; display: flex; flex-direction: column;">
                        <div style="flex-grow: 1;">
                            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem; font-weight: 500; line-height: 1.5;">
                                {{ $org->description ?? 'No description provided for this classroom.' }}
                            </p>
                        </div>
                        <div style="display: flex; gap: 0.75rem; border-top: 1px solid var(--border); padding-top: 1.25rem; margin-top: auto;">
                            <a href="/org/{{ $org->slug }}/dashboard" class="btn btn-primary" style="padding: 0.6rem 1rem; font-size: 0.85rem; flex-grow: 1;">Dashboard</a>
                            <a href="/org/{{ $org->slug }}" class="btn btn-outline" style="padding: 0.6rem 1rem; font-size: 0.85rem; border-color: var(--border);">Public</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @else
        {{-- Public Landing - TRULY CENTERED --}}
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; min-height: 80vh; width: 100%; max-width: 800px;">
            <span style="background: var(--brand-soft); color: var(--brand); padding: 0.6rem 1.2rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; border: 1px solid var(--border); margin-bottom: 2rem;">
                Enterprise Multi-tenant Support
            </span>
            
            <h1 style="font-size: 4.5rem; font-weight: 800; color: var(--brand); letter-spacing: -0.05em; line-height: 1.1; margin-bottom: 1.5rem;">
                Your Classroom,<br><span style="color: var(--accent);">Our Platform.</span>
            </h1>
            
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 3rem; font-weight: 500; line-height: 1.6; max-width: 460px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                Build your own digital tutoring center. Create classrooms, share expertise, and manage your students in one premium workspace.
            </p>
            
            <div style="display: flex; gap: 1.5rem; justify-content: center; align-items: center;">
                <a href="{{ route('google.login') }}" class="btn btn-outline" style="border-width: 2px; padding: 0.875rem 2.5rem;">
                    <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.16H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.84l3.66-2.75z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.16l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Continue with Google
                </a>
                <a href="{{ route('register') }}" class="btn btn-accent" style="padding: 0.875rem 2.5rem;">Create Account</a>
            </div>
        </div>
    @endauth
</div>
@endsection
