@extends('layouts.app')

@section('title', 'Teacher Dashboard - LinkLearn')

@section('content')
<div style="width: 100%; max-width: 1200px;">
    <div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--brand); letter-spacing: -0.04em; margin-bottom: 0.5rem;">Teacher Dashboard</h1>
            <p style="color: var(--text-muted); font-weight: 600;">Manage content and verify payments for your assigned classrooms.</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        <div class="card-modern" style="padding: 1.5rem; display: flex; flex-direction: column; height: 100%;">
            <div style="flex-grow: 1;">
                <h3 style="font-size: 1rem; font-weight: 800; color: var(--brand); margin-bottom: 1rem;">Classroom Management</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;">Oversee your active rooms and student activities.</p>
            </div>
            <div style="background: var(--brand-soft); padding: 1rem; border-radius: 0.75rem; text-align: center; font-weight: 700;">
                {{ $rooms->count() }} Active Rooms
            </div>
        </div>

        <div class="card-modern" style="padding: 1.5rem; display: flex; flex-direction: column; height: 100%;">
            <div style="flex-grow: 1;">
                <h3 style="font-size: 1rem; font-weight: 800; color: var(--brand); margin-bottom: 1rem;">Payment Verification</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;">Review and unlock file access requests from students.</p>
            </div>
            <div style="background: #FEF3C7; color: #92400E; padding: 1rem; border-radius: 0.75rem; text-align: center; font-weight: 700;">
                {{ $pendingCount ?? 0 }} Pending Requests
            </div>
        </div>

        <div class="card-modern" style="padding: 1.5rem; display: flex; flex-direction: column; height: 100%;">
            <div style="flex-grow: 1;">
                <h3 style="font-size: 1rem; font-weight: 800; color: var(--brand); margin-bottom: 1rem;">Pending Join Requests</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;">Review and approve students requesting to join.</p>
            </div>
            <div style="background: #E0E7FF; color: #3730A3; padding: 1rem; border-radius: 0.75rem; text-align: center; font-weight: 700;">
                {{ $pendingJoinCount ?? 0 }} Join Requests
            </div>
        </div>

    </div>

    {{-- Classroom Management Section --}}
    <div style="background: white; border-radius: 1.5rem; border: 1px solid var(--border); overflow: hidden; box-shadow: var(--shadow); margin-bottom: 3rem;">
        <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: var(--surface);">
            <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--brand); margin: 0;">My Active Classrooms</h2>
            <a href="{{ route('rooms.index') }}" class="btn btn-outline" style="font-size: 0.85rem; padding: 0.5rem 1rem;">View All Classrooms</a>
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem 2rem; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">Classroom Name</th>
                    <th style="padding: 1rem 2rem; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">Students</th>
                    <th style="padding: 1rem 2rem; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">Status</th>
                    <th style="padding: 1rem 2rem; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $room)
                <tr style="border-bottom: 1px solid var(--border); transition: 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='none'">
                    <td style="padding: 1.25rem 2rem;">
                        <span style="font-weight: 800; color: var(--brand); font-size: 1rem;">{{ $room->subject_name }}</span>
                    </td>
                    <td style="padding: 1.25rem 2rem;">
                        <span style="font-weight: 700; color: var(--text-muted);">
                            {{ $room->student_count ?? ($room->students?->count() ?? 0) }} enrolled
                        </span>
                    </td>
                    <td style="padding: 1.25rem 2rem;">
                        <span style="color: {{ $room->status === 'open' ? '#10b981' : '#ef4444' }}; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.4rem;">
                            <span style="width: 0.5rem; height: 0.5rem; border-radius: 50%; background: currentColor;"></span>
                            {{ $room->status }}
                        </span>
                    </td>
                    <td style="padding: 1.25rem 2rem;">
                        @if(isset($room->org_slug))
                            <a href="{{ route('rooms.enter', ['room' => $room->id, 'org_slug' => $room->org_slug]) }}" class="btn btn-primary" style="font-size: 0.75rem; padding: 0.4rem 0.8rem;">View</a>
                        @else
                            <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-primary" style="font-size: 0.75rem; padding: 0.4rem 0.8rem;">View</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 5rem 2rem; text-align: center;">

                        <p style="color: var(--text-muted); font-size: 1.1rem; font-weight: 600;">You haven't created any classrooms yet.</p>
                        <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 0.5rem;">Start by clicking the "Create New Room" button.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($pendingRequests) && $pendingRequests->count() > 0)
        <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--brand); margin-bottom: 1rem;">Pending Payment Proofs</h2>
            <div style="display: grid; gap: 1rem;">
                @foreach($pendingRequests as $pending)
                    <div style="border: 1px solid var(--border); border-radius: 0.75rem; padding: 1rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                        <div>
                            <p style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--brand);">{{ $pending->user->name }} ({{ $pending->user->email }})</p>
                            <p style="margin: 0.25rem 0 0; color: var(--text-muted); font-size: 0.9rem;">Requested access to <strong>{{ $pending->file->title }}</strong> in <strong>{{ $pending->file->room->subject_name }}</strong>.</p>
                        </div>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                            @if(isset($pending->org_slug))
                                <a href="{{ route('rooms.tenant-proof', ['org_slug' => $pending->org_slug, 'path' => $pending->proof_of_payment]) }}" target="_blank" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.5rem 0.85rem;">View Proof</a>
                            @else
                                <a href="{{ asset('storage/' . $pending->proof_of_payment) }}" target="_blank" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.5rem 0.85rem;">View Proof</a>
                            @endif
                            @if(isset($pending->org_slug))
                                <a href="{{ route('rooms.enter', ['room' => $pending->file->room->id, 'org_slug' => $pending->org_slug]) }}" class="btn btn-accent" style="font-size: 0.8rem; padding: 0.5rem 0.85rem;">Go to Classroom</a>
                            @else
                                <a href="{{ route('rooms.show', $pending->file->room->id) }}" class="btn btn-accent" style="font-size: 0.8rem; padding: 0.5rem 0.85rem;">Go to Classroom</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(isset($pendingJoinRequests) && $pendingJoinRequests->count() > 0)
        <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.5rem; margin-top: 2rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--brand); margin-bottom: 1rem;">Pending Join Requests</h2>
            <div style="display: grid; gap: 1rem;">
                @foreach($pendingJoinRequests as $join)
                    <div style="border: 1px solid var(--border); border-radius: 0.75rem; padding: 1rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                        <div>
                            <p style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--brand);">{{ $join->student_name }} ({{ $join->student_email }})</p>
                            <p style="margin: 0.25rem 0 0; color: var(--text-muted); font-size: 0.9rem;">Requested to join <strong>{{ $join->room_name }}</strong> in <strong>{{ $join->org_name ?? 'Central System' }}</strong>.</p>
                        </div>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                            @if(isset($join->org_slug))
                                <a href="{{ route('rooms.enter', ['room' => $join->room_id, 'org_slug' => $join->org_slug]) }}" class="btn btn-accent" style="font-size: 0.8rem; padding: 0.5rem 0.85rem;">Review in Classroom</a>
                            @else
                                <a href="{{ route('rooms.show', $join->room_id) }}" class="btn btn-accent" style="font-size: 0.8rem; padding: 0.5rem 0.85rem;">Review in Classroom</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
