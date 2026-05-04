@extends('layouts.app')

@section('title', 'Subscription Expired')

@section('content')
<div class="card-auth">
    <div style="background-color: #fef2f2; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
        <svg style="width: 32px; height: 32px; color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
    <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.75rem;">Access Suspended</h1>
    <p style="color: var(--text-muted); margin-bottom: 2rem;">The subscription for this organization has expired. Please contact your administrator to renew access.</p>
    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
        <a href="/" class="btn btn-primary">Go to Home Page</a>
        <a href="mailto:support@linklearn.com" class="btn btn-outline">Contact Support</a>
    </div>
</div>
@endsection
