@extends('layouts.app')

@section('title', 'Admin - Help and Support')

@section('content')
<div style="max-width: 1000px; margin: auto; padding: 3rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--brand); line-height: 1.2;">Help and Support</h1>
            <p style="color: var(--text-muted); font-weight: 600; margin-top: 0.5rem;">Manage and respond to tenant inquiries.</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.support.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline' }}" style="padding: 0.5rem 1rem; border-radius: 999px; font-size: 0.85rem;">All</a>
            <a href="{{ route('admin.support.index', ['status' => 'open']) }}" class="btn {{ request('status') === 'open' ? 'btn-primary' : 'btn-outline' }}" style="padding: 0.5rem 1rem; border-radius: 999px; font-size: 0.85rem;">Open</a>
            <a href="{{ route('admin.support.index', ['status' => 'closed']) }}" class="btn {{ request('status') === 'closed' ? 'btn-primary' : 'btn-outline' }}" style="padding: 0.5rem 1rem; border-radius: 999px; font-size: 0.85rem;">Closed</a>
        </div>
    </div>

    <div style="background: white; border-radius: 1.5rem; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden;">
        @if(isset($tickets) && $tickets->count() > 0)
            <div style="width: 100%; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8fafc; border-bottom: 1px solid var(--border);">
                        <tr>
                            <th style="padding: 1.25rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Ticket Subject</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Requester</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: left; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Date</th>
                            <th style="padding: 1.25rem 1.5rem; text-align: right; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr style="border-bottom: 1px solid var(--border); transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f1f5f9'" onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding: 1.25rem 1.5rem;">
                                    <div style="font-weight: 700; color: var(--text);">{{ $ticket->subject }}</div>
                                </td>
                                <td style="padding: 1.25rem 1.5rem;">
                                    <div style="font-weight: 600; color: var(--text);">{{ $ticket->user->name ?? 'Unknown' }}</div>
                                    @if($ticket->organization)
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $ticket->organization->name }}</div>
                                    @endif
                                </td>
                                <td style="padding: 1.25rem 1.5rem;">
                                    @if($ticket->status === 'open')
                                        <span style="background: rgba(16, 185, 129, 0.1); color: #10B981; padding: 0.35rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">Open</span>
                                    @else
                                        <span style="background: rgba(107, 114, 128, 0.1); color: #6B7280; padding: 0.35rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">Closed</span>
                                    @endif
                                </td>
                                <td style="padding: 1.25rem 1.5rem; color: var(--text-muted); font-size: 0.9rem;">
                                    {{ $ticket->created_at->format('M d, Y') }}
                                </td>
                                <td style="padding: 1.25rem 1.5rem; text-align: right;">
                                    <a href="{{ route('admin.support.show', $ticket->id) }}" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.8rem;">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="padding: 4rem; text-align: center; color: var(--text-muted);">
                <p style="font-weight: 600; font-size: 1.1rem; margin-bottom: 0.5rem;">No Help and Support Tickets</p>
                <p style="font-size: 0.9rem;">There are currently no tickets matching your criteria.</p>
            </div>
        @endif
    </div>
</div>
@endsection
