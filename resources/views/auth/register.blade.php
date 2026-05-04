@extends('layouts.app')

@section('title', 'Create Account - LinkLearn')

@section('content')
<div style="width: 100%; max-width: 500px; padding: 2rem 0;">
    <div class="card-modern" style="padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--brand); letter-spacing: -0.03em;">Create Account</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Join our educational ecosystem today</p>
        </div>

        <form action="{{ route('register') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf
            
            @if ($errors->any())
                <div style="background: #FEE2E2; color: #B91C1C; padding: 1rem; border-radius: 0.75rem; font-size: 0.85rem; border: 1px solid #FECACA;">
                    <ul style="margin: 0; padding-left: 1.25rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" 
                       style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem;" required>
            </div>

            <div>
                <label style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" 
                       style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem;" required>
            </div>

            <div>
                <label style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">I am registering as</label>
                <select id="role-select" name="role" style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem;">
                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student (Client)</option>
                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher (Tutor)</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Organization Owner (Admin)</option>
                </select>
            </div>

            {{-- Organization Specific Fields (Hidden by default) --}}
            <div id="org-fields" style="display: {{ old('role') == 'admin' ? 'block' : 'none' }}; border: 2px dashed var(--accent); padding: 1.5rem; border-radius: 1rem; background: var(--brand-soft); margin-top: 0.5rem;">
                <h4 style="color: var(--brand); margin-bottom: 0.5rem; font-weight: 800; font-size: 0.9rem;">Organization Details</h4>
                <div>
                    <label style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--brand); display: block; margin-bottom: 0.4rem;">Organization Name</label>
                    <input type="text" id="org_name" name="org_name" value="{{ old('org_name') }}" placeholder="e.g. Bukidnon State University" 
                           style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid var(--border); font-size: 0.9rem;">
                </div>
            </div>

            <div>
                <label style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Create Password</label>
                <input type="password" name="password" placeholder="Min. 8 characters" 
                       style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem;" required>
            </div>

            <button type="submit" class="btn btn-accent" style="width: 100%; padding: 1rem; margin-top: 0.5rem;">Create My Account</button>
        </form>

        <div style="display: flex; align-items: center; gap: 1rem; margin: 1.5rem 0;">
            <div style="flex-grow: 1; height: 1px; background: var(--border);"></div>
            <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">OR</span>
            <div style="flex-grow: 1; height: 1px; background: var(--border);"></div>
        </div>

        <button type="button" onclick="redirectGoogle()" class="btn btn-outline" style="width: 100%; justify-content: center;">
            <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.16H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.84l3.66-2.75z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.16l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Sign up with Google
        </button>

        <p style="text-align: center; margin-top: 2rem; font-size: 0.9rem; color: var(--text-muted); font-weight: 500;">
            Already have an account? <button type="button" onclick="window.location.href='{{ route('login') }}'" style="background: none; border: none; color: var(--accent); font-weight: 700; text-decoration: none; cursor: pointer; font-size: 0.9rem; font-family: inherit; padding: 0;">Login here</button>
        </p>
    </div>
</div>

<script>
    const roleSelect = document.getElementById('role-select');
    const orgFields = document.getElementById('org-fields');
    const orgNameInput = document.getElementById('org_name');

    function toggleOrgFields() {
        if (roleSelect.value === 'admin') {
            orgFields.style.display = 'block';
            orgNameInput.setAttribute('required', 'required');
        } else {
            orgFields.style.display = 'none';
            orgNameInput.removeAttribute('required');
        }
    }

    function redirectGoogle() {
        const role = roleSelect.value;
        const orgName = orgNameInput.value;
        let url = "{{ route('google.login') }}?action=register&role=" + encodeURIComponent(role);
        
        if (role === 'admin' && orgName.trim() === '') {
            alert('Please enter your Organization Name before signing up with Google.');
            orgNameInput.focus();
            return;
        }

        if (role === 'admin') {
            url += "&org_name=" + encodeURIComponent(orgName);
        }

        window.location.href = url;
    }

    roleSelect.addEventListener('change', toggleOrgFields);
    window.onload = toggleOrgFields;
</script>
@endsection
