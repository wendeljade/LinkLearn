@extends('layouts.app')

@section('title', 'Support')

@section('content')
<div style="max-width: 900px; margin: auto; padding: 3rem 0;">
    <div style="background: white; padding: 2.5rem; border-radius: 1.5rem; border: 1px solid var(--border); box-shadow: var(--shadow);">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--brand);">Support & Updates</h1>
        <p style="color: var(--text-muted); margin-top: 1rem; line-height: 1.75;">If you need help with your tutoring center, enrollment flow, or platform settings, we are here to assist. Use the contact information below to connect with support.</p>

        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.5rem; margin-top: 2rem;">
            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 1.25rem;">
                <h2 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 0.75rem;">Contact</h2>
                <p style="color: var(--text-muted); margin-bottom: 0.5rem;">Email: <strong>support@linklearn.com</strong></p>
                <p style="color: var(--text-muted);">Phone: <strong>+63 917 123 4567</strong></p>
            </div>
            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 1.25rem;">
                <h2 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 0.75rem;">Updates</h2>
                <p style="color: var(--text-muted); margin-bottom: 0.5rem;">Platform updates are released regularly to improve feature management, security, and tenant experience.</p>
                <p style="color: var(--text-muted);">Bookmark this page or contact support for release notes and upgrade information.</p>
            </div>
        </div>
    </div>
</div>
@endsection
