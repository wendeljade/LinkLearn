@extends('layouts.app')

@section('title', strtoupper($org->name) . ' - Dashboard')

@section('content')
<div style="width: 100%; max-width: 1100px;">
    {{-- Header Section --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--brand); letter-spacing: -0.04em;">
                {{ strtoupper($org->name) }}
            </h1>
            <p style="color: var(--text-muted); font-weight: 600;">Classroom Monitoring & Management</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <a href="/settings" class="btn btn-outline" style="padding: 0.6rem 1rem; font-size: 0.85rem; border-color: var(--border);">Settings</a>
            <a href="/team" class="btn btn-outline" style="padding: 0.6rem 1rem; font-size: 0.85rem; border-color: var(--border);">Team</a>
            @if(auth()->user()->role === 'org_admin' || auth()->user()->isAdmin())
                <a href="{{ route('org.rooms.create') }}" class="btn btn-accent" style="padding: 0.6rem 1rem; font-size: 0.85rem;">+ Create Room</a>
            @endif
        </div>
    </div>

    {{-- Summary Cards --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 3rem;">
        <div style="background: white; padding: 1.25rem; border-radius: 1.25rem; border: 1px solid var(--border); box-shadow: var(--shadow);">
            <p style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.5rem;">Total Tutors</p>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--brand);">{{ $totalTutors }}</h2>
        </div>
        <div style="background: white; padding: 1.25rem; border-radius: 1.25rem; border: 1px solid var(--border); box-shadow: var(--shadow);">
            <p style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.5rem;">Total Students</p>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--brand);">{{ $totalStudents }}</h2>
        </div>
        <div style="background: white; padding: 1.25rem; border-radius: 1.25rem; border: 1px solid var(--border); box-shadow: var(--shadow);">
            <p style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.5rem;">Active Rooms</p>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--brand);">{{ $totalActiveRooms }}</h2>
        </div>
        <div style="background: var(--brand); padding: 1.25rem; border-radius: 1.25rem; border: 1px solid var(--brand);">
            <p style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: rgba(255,255,255,0.6); letter-spacing: 0.05em; margin-bottom: 0.5rem;">Total Income</p>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--accent);">₱{{ number_format($totalIncome, 2) }}</h2>
        </div>
    </div>

    {{-- Classroom List --}}
    <div style="background: white; border-radius: 1.5rem; border: 1px solid var(--border); overflow: hidden; box-shadow: var(--shadow);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--surface); border-bottom: 1px solid var(--border);">
                    <th style="padding: 1.25rem 1.5rem; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">Subject Name</th>
                    <th style="padding: 1.25rem 1.5rem; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">Tutor</th>
                    <th style="padding: 1.25rem 1.5rem; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">Students</th>
                    <th style="padding: 1.25rem 1.5rem; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">Status</th>
                    <th style="padding: 1.25rem 1.5rem; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; width: 80px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $room)
                <tr style="border-bottom: 1px solid var(--border); transition: 0.2s;" onmouseover="this.style.background='var(--surface)'" onmouseout="this.style.background='none'">
                    <td style="padding: 1.25rem 1.5rem;">
                        <span style="font-weight: 800; color: var(--brand); font-size: 1rem;">{{ $room->subject_name }}</span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <img src="{{ $room->tutor?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($room->tutor?->name ?? 'N/A') }}" style="width: 2rem; height: 2rem; border-radius: 50%;">
                            <span style="font-weight: 600; font-size: 0.9rem;">{{ $room->tutor?->name ?? 'Unassigned' }}</span>
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <span style="font-weight: 700; color: var(--text-muted);">{{ $room->students->count() }}</span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <span style="color: {{ $room->status === 'open' ? '#10b981' : ($room->status === 'full' ? '#f59e0b' : '#ef4444') }}; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.4rem;">
                            <span style="width: 0.5rem; height: 0.5rem; border-radius: 50%; background: currentColor;"></span>
                            {{ $room->status }}
                        </span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <a href="{{ route('org.rooms.show', $room->id) }}" style="background: none; border: none; color: var(--brand); cursor: pointer; padding: 0.5rem; display: inline-flex;" title="View Details">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 4rem; text-align: center;">
                        <p style="color: var(--text-muted); font-size: 1.1rem; font-weight: 600;">No rooms created yet.</p>
                        <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 0.5rem;">Start by clicking "+ Create Room".</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
