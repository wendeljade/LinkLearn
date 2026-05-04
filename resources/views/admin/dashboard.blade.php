@extends('layouts.app')

@section('title', 'Super Admin Dashboard - LinkLearn')

@section('content')
<div style="width: 100%; max-width: 1200px;">
    <div style="margin-bottom: 3.5rem;">
        <h1 style="font-size: 2.75rem; font-weight: 800; color: var(--brand); letter-spacing: -0.04em; margin-bottom: 0.5rem;">Super Admin Dashboard</h1>
        <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Platform-Wide Management and Global Monitoring.</p>
    </div>

    {{-- Dashboard Stats Cards --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 4rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--border);">
            <p style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.5rem; letter-spacing: 0.05em;">Total Organizations</p>
            <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--brand);">{{ $totalOrgs }}</h2>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--border);">
            <p style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.5rem; letter-spacing: 0.05em;">Total Active Classrooms</p>
            <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--brand);">{{ $totalActiveRooms }}</h2>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--border);">
            <p style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.5rem; letter-spacing: 0.05em;">Total Users</p>
            <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--brand);">{{ $totalUsers }}</h2>
        </div>
        <div style="background: var(--brand); padding: 1.5rem; border-radius: 1rem;">
            <p style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: rgba(255,255,255,0.6); margin-bottom: 0.5rem; letter-spacing: 0.05em;">Total Global Revenue</p>
            <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--accent);">₱{{ number_format($totalIncome, 2) }}</h2>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1.8fr 1fr; gap: 4rem;">
        {{-- Organization Management Section --}}
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--brand);">Organization Management</h3>
                <a href="{{ route('admin.organizations') }}" style="font-size: 0.9rem; font-weight: 800; color: var(--accent); text-decoration: underline;">View All</a>
            </div>
            
            <div style="background: white; border: 1px solid var(--border); border-radius: 0.75rem; padding: 2rem; text-align: center;">
                @if($recentOrganizations->isEmpty())
                    <p style="color: var(--text-muted); font-weight: 600;">Recent organizations will appear here.</p>
                @else
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <th style="padding: 1rem 0; color: var(--text-muted); font-weight: 700;">Name</th>
                                <th style="padding: 1rem 0; color: var(--text-muted); font-weight: 700;">Owner</th>
                                <th style="padding: 1rem 0; color: var(--text-muted); font-weight: 700; text-align: right;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrganizations as $org)
                            <tr style="border-bottom: 1px solid var(--brand-soft);">
                                <td style="padding: 1.25rem 0; font-weight: 700; color: var(--brand);">{{ $org->name }}</td>
                                <td style="padding: 1.25rem 0; color: var(--text-muted);">{{ optional($org->owner)->name ?? 'N/A' }}</td>
                                <td style="padding: 1.25rem 0; text-align: right;">
                                    <span style="background: {{ $org->status === 'active' ? 'var(--brand-soft)' : '#fee2e2' }}; color: {{ $org->status === 'active' ? 'var(--brand)' : '#b91c1c' }}; padding: 0.35rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">
                                        {{ $org->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- Pending Approvals Section --}}
        <div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; gap: 1rem;">
                <div>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--brand); margin-bottom: 0.25rem;">Pending Approval</h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 380px;">Review recent tenant payment proof and approve organizations before they can access their dashboard.</p>
                </div>
                <a href="{{ route('admin.organizations') }}" style="font-size: 0.9rem; font-weight: 800; color: var(--accent); text-decoration: underline; white-space: nowrap; flex-shrink: 0;">View All</a>
            </div>
            
            <div style="background: white; border: 1px solid var(--border); border-radius: 1rem; padding: 1.5rem;">
                @if($pendingOrganizations->isEmpty())
                    <div style="padding: 3rem 2rem; text-align: center;">
                        <p style="color: var(--text-muted); font-weight: 600;">No organizations waiting for approval.</p>
                    </div>
                @else
                    <div style="display: grid; gap: 1rem;">
                        @foreach($pendingOrganizations as $org)
                        <div style="display: grid; gap: 1rem; background: #f8fafc; border: 1px solid rgba(14, 165, 233, 0.16); border-radius: 1rem; padding: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                                <div style="flex: 1; min-width: 0; display: flex; flex-direction: column; justify-content: center;">
                                    <h4 style="font-weight: 800; color: var(--brand); margin-bottom: 0.5rem; font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $org->name }}">{{ $org->name }}</h4>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                        <span style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.25rem 0.6rem; border-radius: 999px; background: #fef3c7; color: #92400e; font-weight: 700; font-size: 0.7rem; text-transform: uppercase;">Pending Approval</span>
                                        <span style="color: var(--text-muted); font-size: 0.85rem;">Paid: <strong>{{ optional($org->subscription_paid_at)->format('M d, Y') ?? 'Not yet submitted' }}</strong></span>
                                    </div>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 0.75rem; align-items: flex-end;">
                                    @if($org->proof_of_payment)
                                        <a href="{{ route('admin.proofs.view', basename($org->proof_of_payment)) }}" target="_blank" class="btn btn-outline" style="padding: 0.75rem 1rem; font-size: 0.85rem;">View Proof</a>
                                    @endif
                                    <form action="{{ route('admin.org.approve', $org->slug) }}" method="POST" style="margin: 0; width: 100%;">
                                        @csrf
                                        <button type="submit" class="btn btn-accent" style="width: 100%; padding: 0.75rem 1rem; font-size: 0.85rem;">Approve</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Global Monitoring Section --}}
        <div>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--brand); margin-bottom: 2.5rem;">Global Monitoring</h3>
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">
                    <span style="font-size: 1.1rem; font-weight: 700; color: var(--brand);">Total Teachers</span>
                    <span style="font-size: 1.1rem; font-weight: 800; color: var(--brand);">{{ $totalTeachers }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">
                    <span style="font-size: 1.1rem; font-weight: 700; color: var(--brand);">Total Students</span>
                    <span style="font-size: 1.1rem; font-weight: 800; color: var(--brand);">{{ $totalStudents }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">
                    <span style="font-size: 1.1rem; font-weight: 700; color: var(--brand);">Active Classrooms</span>
                    <span style="font-size: 1.1rem; font-weight: 800; color: var(--brand);">{{ $totalActiveRooms }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
