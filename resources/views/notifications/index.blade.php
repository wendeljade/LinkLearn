@extends('layouts.app')

@section('title', 'Notifications - LinkLearn')

@section('content')
<div style="width: 100%; max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: 800; color: var(--brand); letter-spacing: -0.04em;">Notifications</h1>
        
        @if($notifications->whereNull('read_at')->count() > 0)
            <form action="{{ (function_exists('tenant') && tenant()) ? route('org.notifications.read-all') : route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Mark All as Read</button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 1rem; border-radius: 0.75rem; margin-bottom: 2rem; border: 1px solid #a7f3d0; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: #fff; border-radius: 1rem; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden;">
        @forelse($notifications as $notification)
            <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; gap: 1rem; background: {{ $notification->read_at ? '#fff' : '#f8fafc' }}; transition: 0.2s;" class="notification-item">
                <div style="font-size: 1.5rem; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: var(--surface); border-radius: 50%;">
                    {{ $notification->icon ?? '🔔' }}
                </div>
                
                <div style="flex-grow: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.25rem;">
                        <h3 style="font-size: 1rem; font-weight: 700; color: var(--brand); margin: 0;">
                            {{ $notification->title }}
                            @if(!$notification->read_at)
                                <span style="display: inline-block; width: 8px; height: 8px; background: var(--accent); border-radius: 50%; margin-left: 0.5rem;"></span>
                            @endif
                        </h3>
                        <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <p style="color: var(--text-main); font-size: 0.9rem; margin: 0 0 0.5rem 0;">{{ $notification->message }}</p>
                    
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        @if($notification->link)
                            <a href="{{ $notification->link }}" style="font-size: 0.85rem; font-weight: 700; color: var(--accent); text-decoration: none;">View Details →</a>
                        @endif
                        

                        
                        <form action="{{ (function_exists('tenant') && tenant()) ? route('org.notifications.destroy', $notification) : route('notifications.destroy', $notification) }}" method="POST" style="margin: 0; margin-left: auto;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc2626; font-size: 0.8rem; font-weight: 600; cursor: pointer;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">All caught up!</h3>
                <p>You don't have any notifications right now.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div style="margin-top: 2rem;">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
