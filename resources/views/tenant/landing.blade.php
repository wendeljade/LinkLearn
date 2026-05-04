@extends('layouts.app')

@section('title', 'Welcome to ' . $org->name)

@section('content')
<div style="width: 100%; display: flex; flex-direction: column; align-items: center;">
    {{-- Hero Section with Cover Photo --}}
    <div style="width: 100%; max-width: 900px; background: white; border-radius: 1.5rem; overflow: hidden; border: 1px solid var(--border); box-shadow: var(--shadow);">
        <div style="height: 240px; background: {{ $org->cover_photo ? 'url('.asset('storage/'.$org->cover_photo).')' : 'var(--brand)' }}; background-size: cover; background-position: center; position: relative;">
            <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(15,23,42,0.1), rgba(15,23,42,0.8));"></div>
            <div style="position: absolute; bottom: 2rem; left: 2.5rem;">
                <h1 style="font-size: 3rem; font-weight: 800; color: white; letter-spacing: -0.04em; margin-bottom: 0.5rem;">
                    {{ $org->name }}
                </h1>
                <p style="color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.85rem;">
                    Official Tutoring Center
                </p>
            </div>
        </div>

        <div style="padding: 3rem 2.5rem; text-align: left;">
            <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 2.5rem; line-height: 1.7; max-width: 700px;">
                {{ $org->description ?? 'Welcome to our digital workspace. Here you can access tutoring materials, connect with experts, and track your learning progress.' }}
            </p>

            <div style="display: flex; gap: 1.25rem; align-items: center; flex-wrap: wrap;">
                @if(auth()->check() && $org->user_id === auth()->id())
                    @if($org->status === 'pending_payment')
                        <a href="{{ route('org.subscription.payment', $org->slug) }}" class="btn btn-accent" style="padding: 1rem 2.5rem;">Pay Subscription & Upload Proof</a>
                    @elseif($org->status === 'pending_approval')
                        <span class="btn btn-outline" style="padding: 1rem 2.5rem; border-color: var(--border); color: var(--brand);">Waiting for Super Admin Approval</span>
                    @elseif($org->status === 'deactive' || $org->status === 'expired')
                        <span class="btn btn-outline" style="padding: 1rem 2.5rem; border-color: var(--border); color: var(--brand);">Organization Disabled</span>
                    @else
                        <a href="{{ route('org.admin.dashboard') }}" class="btn btn-primary" style="padding: 1rem 2.5rem;">Go to Admin Dashboard</a>
                    @endif
                @elseif(auth()->check())
                    <a href="{{ route('org.rooms.index') }}" class="btn btn-accent" style="padding: 1rem 2.5rem;">View Classrooms</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-accent" style="padding: 1rem 2.5rem;">Login to Enroll</a>
                @endif
                
                <a href="{{ route('org.files') }}" class="btn btn-outline" style="padding: 1rem 2.5rem; border-color: var(--border);">
                    Browse Public Materials
                </a>
            </div>
        </div>
    </div>

    {{-- Stats or Footer info --}}
    <div style="margin-top: 3rem; display: flex; gap: 4rem;">
        <div style="text-align: center;">
            <p style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.25rem;">Active Sessions</p>
            <p style="font-size: 1.5rem; font-weight: 800; color: var(--brand);">{{ $org->rooms->count() }}</p>
        </div>
        <div style="text-align: center;">
            <p style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.25rem;">Student Hub</p>
            <p style="font-size: 1.5rem; font-weight: 800; color: var(--brand);">Verified Access</p>
        </div>
    </div>
</div>
@endsection
