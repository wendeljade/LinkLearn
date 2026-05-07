@extends('layouts.app')

@section('title', 'Support')

@section('content')
<div style="max-width: 900px; margin: auto; padding: 3rem 0;">
    <div style="background: white; padding: 2.5rem; border-radius: 1.5rem; border: 1px solid var(--border); box-shadow: var(--shadow);">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--brand);">Support & Updates</h1>
        <p style="color: var(--text-muted); margin-top: 1rem; line-height: 1.75;">If you need help with your tutoring center, enrollment flow, or platform settings, we are here to assist. Open a support ticket below to chat directly with our team.</p>

        <!-- New Ticket Form -->
        <div style="margin-top: 2.5rem; padding: 2rem; background: #f8fafc; border-radius: 1.25rem; border: 1px solid var(--border);">
            <h2 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1rem; color: var(--brand);">Open a New Ticket</h2>
            <form action="{{ request()->routeIs('org.*') ? route('org.support.store') : route('support.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem;">Subject</label>
                    <input type="text" name="subject" required class="form-input" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem;" placeholder="E.g. Issue with classroom payments">
                </div>
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem;">Message</label>
                    <textarea name="message" required class="form-input" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem; min-height: 100px;" placeholder="Describe your issue..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">Submit Ticket</button>
            </form>
        </div>

        <!-- Existing Tickets -->
        <div style="margin-top: 3rem;">
            <h2 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 1.5rem; color: var(--brand);">Your Support Tickets</h2>
            @if(isset($tickets) && $tickets->count() > 0)
                <div style="display: grid; gap: 1rem;">
                    @foreach($tickets as $ticket)
                        @php
                            $route = request()->routeIs('org.*') ? route('org.support.show', $ticket->id) : route('support.show', $ticket->id);
                        @endphp
                        <a href="{{ $route }}" style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; background: #fff; border: 1px solid var(--border); border-radius: 1rem; text-decoration: none; color: inherit; transition: all 0.2s ease;">
                            <div>
                                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $ticket->subject }}</h3>
                                <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">Created {{ $ticket->created_at->diffForHumans() }}</p>
                            </div>
                            <div>
                                @if($ticket->status === 'open')
                                    <span style="background: rgba(16, 185, 129, 0.1); color: #10B981; padding: 0.35rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">Open</span>
                                @else
                                    <span style="background: rgba(107, 114, 128, 0.1); color: #6B7280; padding: 0.35rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">Closed</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div style="padding: 2rem; border: 1px dashed var(--border); border-radius: 1rem; text-align: center; color: var(--text-muted);">
                    You don't have any support tickets yet.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
