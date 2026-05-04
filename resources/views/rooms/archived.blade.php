@extends('layouts.app')

@section('title', 'Archived Classrooms - LinkLearn')

@section('content')
<div style="width: 100%; max-width: 1200px;">
    <div style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--brand); letter-spacing: -0.04em; margin-bottom: 0.5rem;">Archived Classrooms</h1>
        <p style="color: var(--text-muted); font-weight: 600;">Manage your archived educational spaces.</p>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 1rem; border-radius: 0.75rem; margin-bottom: 2rem; border: 1px solid #a7f3d0; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
        @forelse($rooms as $room)
            <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; overflow: hidden; display: flex; flex-direction: column; opacity: 0.8; filter: grayscale(0.5); transition: 0.3s;" onmouseover="this.style.opacity='1'; this.style.filter='none';">
                
                <div style="height: 100px; background: {{ $room->cover_photo ? 'url(' . (function_exists('tenant') && tenant() ? tenant_asset($room->cover_photo) : asset('storage/' . $room->cover_photo)) . ')' : 'var(--brand)' }}; background-size: cover; background-position: center; padding: 1.25rem; color: #fff; position: relative;">
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, var(--brand) 0%, transparent 100%); z-index: 1;"></div>
                    <div style="position: relative; z-index: 2;">
                        <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 0.2rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $room->subject_name }}</h3>
                        <p style="font-size: 0.8rem; font-weight: 600; color: var(--accent);">Archived</p>
                    </div>
                </div>

                <div style="padding: 1.25rem; flex-grow: 1;">
                    <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $room->description ?? 'No description available.' }}
                    </p>
                </div>

                <div style="padding: 0.75rem 1.25rem; border-top: 1px solid var(--brand-soft); display: flex; justify-content: flex-end; background: #fff;">
                    @php
                        $roomOrgSlug = $room->org_slug ?? (isset($org) ? $org->slug : null);
                        if ($roomOrgSlug) {
                            $unarchiveRoute = isset($org) ? route('org.rooms.unarchive', $room->id) : route('rooms.tenant-unarchive', ['room' => $room->id, 'org_slug' => $roomOrgSlug]);
                        } else {
                            $unarchiveRoute = route('rooms.unarchive', $room->id);
                        }
                    @endphp
                    <form action="{{ $unarchiveRoute }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="color: var(--brand); background: var(--accent); font-weight: 700; font-size: 0.75rem; padding: 0.5rem 1.25rem; border: none; border-radius: 0.5rem; cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em; transition: 0.2s; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.1);" onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-1px)';" onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)';" >
                            Restore Room
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; background: white; border: 2px dashed var(--border); border-radius: 1.25rem; padding: 4rem; text-align: center;">
                <p style="color: var(--text-muted); font-size: 1.1rem; font-weight: 600;">No archived classrooms.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
