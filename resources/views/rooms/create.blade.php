@extends('layouts.app')

@section('title', 'Create Classroom - LinkLearn')

@section('content')
<div style="width: 100%; max-width: 600px; padding: 2rem 0;">
    <div class="card-modern" style="padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--brand); letter-spacing: -0.03em;">Create New Classroom</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Set up your classroom space for students.</p>
        </div>

        <form action="{{ isset($org) ? route('org.rooms.store') : route('rooms.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.5rem;">
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
                <label style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Classroom Name</label>
                <input type="text" name="subject_name" value="{{ old('subject_name') }}" placeholder="e.g. Advanced Mathematics" 
                       style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem;" required>
            </div>

            <div>
                <label style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Description</label>
                <textarea name="description" rows="3" placeholder="Briefly describe what this classroom is about..." 
                          style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem; resize: none;">{{ old('description') }}</textarea>
            </div>

            <div>
                <label style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Cover Photo</label>
                <input type="file" id="cover_photo_create" name="cover_photo" accept="image/*" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem; font-size: 0.95rem; display: block;">
                <small style="display: block; margin-top: 0.25rem; color: var(--text-muted);">PNG, JPG, GIF up to 5MB (Optional)</small>
            </div>

            <button type="submit" class="btn btn-accent" style="width: 100%; padding: 1rem; margin-top: 0.5rem;">Publish Classroom</button>
        </form>
    </div>
</div>
@endsection
