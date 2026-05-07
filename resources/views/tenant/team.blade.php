@extends('layouts.app')

@section('title', 'Team Management - ' . $org->name)

@section('content')
<div style="width: 100%; max-width: 1000px; margin: auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--brand);">Team Management</h1>
            <p style="color: var(--text-muted);">View all accounts that are part of your organization.</p>
        </div>
        <a href="{{ route('org.admin.dashboard') }}" class="btn btn-outline" style="padding: 0.8rem 1.5rem;">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #d1fae5; color: #166534; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1rem;">{{ session('error') }}</div>
    @endif

    <div style="display: grid; gap: 1.5rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 1.25rem; border: 1px solid var(--border); box-shadow: var(--shadow);">
            <div style="display: grid; gap: 1rem;">
                @forelse($teamUsers as $user)
                    <div style="padding: 1rem; border-radius: 1rem; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                        <div>
                            <p style="font-weight: 700; margin-bottom: 0.25rem;">{{ $user->name }}</p>
                            <p style="color: var(--text-muted); margin: 0;">{{ $user->email }} • {{ strtoupper($user->role) }}</p>
                        </div>
                        <span style="background: {{ $user->role === 'org_admin' ? '#c7d2fe' : ($user->role === 'tutor' || $user->role === 'teacher' ? '#d1fae5' : '#fef9c3') }}; color: {{ $user->role === 'org_admin' ? '#3730a3' : ($user->role === 'tutor' || $user->role === 'teacher' ? '#166534' : '#713f12') }}; padding: 0.55rem 0.9rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">{{ strtoupper($user->role) }}</span>
                    </div>
                @empty
                    <p style="color: var(--text-muted);">No team members have been added yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
