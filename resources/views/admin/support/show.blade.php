@extends('layouts.app')

@section('title', 'Admin - Ticket: ' . $ticket->subject)

@section('content')
<div style="max-width: 900px; margin: auto; padding: 3rem 0;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <a href="{{ route('admin.support.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-muted); text-decoration: none; font-weight: 600; margin-bottom: 1rem; transition: color 0.2s;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Tickets
            </a>
            <h1 style="font-size: 2rem; font-weight: 800; color: var(--brand); line-height: 1.2;">{{ $ticket->subject }}</h1>
            <p style="color: var(--text-muted); margin-top: 0.5rem; font-size: 0.9rem;">
                Requested by: <strong>{{ $ticket->user->name ?? 'Unknown' }}</strong> 
                @if($ticket->organization)
                    (Org: {{ $ticket->organization->name }})
                @endif
                • Created {{ $ticket->created_at->format('M d, Y h:i A') }}
            </p>
        </div>
        <div>
            @if($ticket->status === 'open')
                <form action="{{ route('admin.support.reply', $ticket->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="close_ticket" value="1">
                    <button type="submit" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem; border-color: #ef4444; color: #ef4444;" onclick="return confirm('Are you sure you want to close this ticket?')">Mark as Closed</button>
                </form>
            @else
                <span style="background: rgba(107, 114, 128, 0.1); color: #6B7280; padding: 0.5rem 1rem; border-radius: 999px; font-size: 0.85rem; font-weight: 800;">Closed</span>
            @endif
        </div>
    </div>

    <!-- Messages Thread -->
    <div style="background: white; border-radius: 1.5rem; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden;">
        <div style="padding: 2rem; display: flex; flex-direction: column; gap: 1.5rem; max-height: 60vh; overflow-y: auto; background: #f8fafc;">
            @foreach($ticket->messages as $message)
                @php
                    $isAdminReply = $message->user->role === 'super_admin';
                @endphp
                <div style="display: flex; {{ $isAdminReply ? 'justify-content: flex-end;' : 'justify-content: flex-start;' }}">
                    <div style="max-width: 75%; display: flex; flex-direction: column; {{ $isAdminReply ? 'align-items: flex-end;' : 'align-items: flex-start;' }}">
                        <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; margin-bottom: 0.3rem; padding: 0 0.5rem;">
                            {{ $isAdminReply ? 'You (Support)' : $message->user->name }} • {{ $message->created_at->diffForHumans() }}
                        </span>
                        <div style="padding: 1rem 1.25rem; border-radius: 1rem; font-size: 0.95rem; line-height: 1.5; {{ $isAdminReply ? 'background: var(--brand); color: white; border-bottom-right-radius: 0.25rem;' : 'background: white; border: 1px solid var(--border); color: var(--text); border-bottom-left-radius: 0.25rem;' }}">
                            {!! nl2br(e($message->message)) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Reply Form -->
        <div style="padding: 1.5rem; border-top: 1px solid var(--border); background: white;">
            @if($ticket->status === 'open')
                <form action="{{ route('admin.support.reply', $ticket->id) }}" method="POST">
                    @csrf
                    <div style="display: flex; align-items: center; gap: 0.75rem; background: #f1f5f9; padding: 0.5rem; border-radius: 999px;">
                        <input type="text" name="message" required class="form-input" style="flex-grow: 1; padding: 0.75rem 1.25rem; border: none; background: transparent; outline: none; font-size: 0.95rem; box-shadow: none;" placeholder="Type your response to the user..." autocomplete="off">
                        <button type="submit" style="background: var(--brand); color: white; width: 40px; height: 40px; border-radius: 50%; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; flex-shrink: 0;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" title="Send">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transform: translateX(-1px) translateY(1px);">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </button>
                    </div>
                </form>
            @else
                <div style="text-align: center; color: var(--text-muted); padding: 1rem; font-weight: 600; background: #f1f5f9; border-radius: 1rem;">
                    This ticket has been closed. No further replies can be added.
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const messagesContainer = document.querySelector('.max-height\\: 60vh');
        if(messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    });
</script>
@endsection
