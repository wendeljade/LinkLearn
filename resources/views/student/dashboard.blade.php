@extends('layouts.app')

@section('title', 'Student Dashboard - LinkLearn')

@section('content')
<div style="width: 100%; max-width: 1200px;">
    <div style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--brand); letter-spacing: -0.04em; margin-bottom: 0.5rem;">Student Dashboard</h1>
        <p style="color: var(--text-muted); font-weight: 600;">Access learning materials and join active classrooms.</p>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        {{-- My Learning --}}
        <div class="card-modern" style="padding: 2rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--brand); margin-bottom: 1.5rem;">My Learning Materials</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Purchased learning resources and unlocked files.</p>
            @if(isset($purchases) && $purchases->count() > 0)
                <div style="display: grid; gap: 1rem;">
                    @foreach($purchases as $purchase)
                        <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1rem; display: grid; grid-template-columns: 1fr auto; align-items: center; gap: 1rem;">
                            <div>
                                <p style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--brand);">{{ $purchase->file->title }}</p>
                                <p style="margin: 0.4rem 0 0; color: var(--text-muted); font-size: 0.9rem;">{{ $purchase->file->room->subject_name }} • ₱{{ number_format($purchase->file->price, 2) }}</p>
                            </div>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <a href="{{ route('rooms.preview-file', $purchase->file->id) }}" target="_blank" class="btn btn-outline" style="font-size: 0.85rem; padding: 0.6rem 1rem;">Preview</a>
                                <a href="{{ route('rooms.download-file', $purchase->file->id) }}" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.6rem 1rem;">Download</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="border: 1px solid var(--border); border-radius: 0.75rem; padding: 2rem; text-align: center; color: var(--text-muted);">
                    You haven't purchased any materials yet. <br><small>Unlock files for ₱200 each.</small>
                </div>
            @endif
        </div>

        {{-- Classroom Access --}}
        <div class="card-modern" style="padding: 2rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--brand); margin-bottom: 1.5rem;">My Classrooms</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @php
                    // allJoinedRooms() queries all tenant DBs — shows classrooms from every organization
                    $joinedRooms    = auth()->user()->allJoinedRooms();
                    $centralDomain  = config('tenancy.central_domains')[0] ?? 'localhost';
                    $port           = request()->getPort();
                    $portStr        = ($port && $port != 80 && $port != 443) ? ':' . $port : '';
                    $scheme         = request()->getScheme();
                @endphp

                @forelse($joinedRooms as $room)
                    @php
                        // Use central enter route which generates a magic token for the tenant subdomain
                        $enterUrl = route('rooms.enter', ['room' => $room->id, 'org_slug' => $room->org_slug]);
                    @endphp
                    <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                        <div style="flex: 1; min-width: 0;">
                            <p style="margin: 0; font-weight: 800; color: var(--brand); font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $room->subject_name }}</p>
                            <p style="margin: 0.2rem 0 0; font-size: 0.75rem; color: var(--text-muted);">
                                <span style="background: var(--brand-soft); color: var(--brand); border-radius: 999px; padding: 0.1rem 0.6rem; font-weight: 700; font-size: 0.7rem;">{{ $room->org_name }}</span>
                            </p>
                        </div>
                        <a href="{{ $enterUrl }}" class="btn btn-primary" style="font-size: 0.75rem; padding: 0.4rem 0.8rem; flex-shrink: 0;">Enter</a>
                    </div>
                @empty
                    <div style="text-align: center; padding: 1.5rem 0;">
                        <img src="{{ asset('images/room-invisible.png') }}" alt="No Rooms" style="width: 120px; margin-bottom: 1rem; opacity: 0.6;">
                        <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">You haven't joined any classrooms yet.</p>
                        <a href="{{ route('rooms.index') }}" style="font-size: 0.85rem; color: var(--accent); font-weight: 800; text-decoration: none; display: inline-block; margin-top: 0.5rem;">Explore Available Rooms</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
