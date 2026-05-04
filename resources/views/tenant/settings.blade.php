@extends('layouts.app')

@section('title', 'Classroom Settings - ' . $org->name)

@section('content')
<div style="width: 100%; max-width: 800px;">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2.5rem;">
        <a href="{{ route('org.admin.dashboard') }}" class="btn btn-outline" style="padding: 0.5rem; border-radius: 50%; width: 2.5rem; height: 2.5rem;">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 style="font-size: 2rem; font-weight: 800; color: var(--brand); letter-spacing: -0.03em;">Classroom Settings</h1>
    </div>

    <div class="card-modern" style="text-align: left; padding: 2.5rem;">
        <form action="{{ route('org.update') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.5rem;">
            @csrf
            
            {{-- Classroom Name --}}
            <div>
                <label style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Classroom Name</label>
                <input type="text" name="name" value="{{ old('name', $org->name) }}" 
                       style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem;" required>
            </div>

            {{-- Description --}}
            <div>
                <label style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Description</label>
                <textarea name="description" rows="4" 
                          style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem; resize: none;">{{ old('description', $org->description) }}</textarea>
            </div>

            {{-- Cover Photo Preview & Upload --}}
            <div>
                <label style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Cover Photo</label>
                
                @if($org->cover_photo)
                    <div style="margin-bottom: 1rem; border-radius: 0.75rem; overflow: hidden; height: 150px; border: 1px solid var(--border);">
                        <img src="/storage/{{ $org->cover_photo }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                @endif

                <div style="position: relative; border: 2px dashed var(--border); border-radius: 0.75rem; padding: 1.5rem; text-align: center; transition: 0.2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                    <input type="file" name="cover_photo" accept="image/*" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    <div style="color: var(--text-muted);">
                        <svg style="width: 2rem; height: 2rem; margin-bottom: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        <p style="font-size: 0.85rem; font-weight: 600;">{{ $org->cover_photo ? 'Change cover photo' : 'Upload cover photo' }}</p>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="submit" class="btn btn-accent" style="flex-grow: 1;">Save Changes</button>
                <a href="{{ route('org.admin.dashboard') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
