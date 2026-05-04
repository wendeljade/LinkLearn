@extends('layouts.app')

@section('title', 'Classrooms - LinkLearn')

@section('content')
<div style="width: 100%; max-width: 1200px;">
    <div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--brand); letter-spacing: -0.04em; margin-bottom: 0.5rem;">
                @if(isset($org))
                    {{ $org->name . ' Classrooms' }}
                @elseif(auth()->check() && (auth()->user()->isTeacher() || auth()->user()->isStudent()))
                    My Classrooms
                @else
                    Available Classrooms
                @endif
            </h1>
            <p style="color: var(--text-muted); font-weight: 600;">Explore our available rooms and start learning.</p>
        </div>
        @if(auth()->check() && (auth()->user()->isTeacher() || auth()->user()->isAdmin()))
            <a href="{{ isset($org) ? route('org.rooms.create') : route('rooms.create') }}" class="btn btn-accent">+ Create New Room</a>
        @endif
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 1rem; border-radius: 0.75rem; margin-bottom: 2rem; border: 1px solid #a7f3d0; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
        @forelse($rooms as $room)
            <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; overflow: hidden; display: flex; flex-direction: column; transition: 0.3s; position: relative;" onmouseover="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                
                {{-- Google Classroom Style Header with Blue Fade Motif --}}
                <div style="height: 120px; background: {{ $room->coverPhotoUrl() ? 'url(' . $room->coverPhotoUrl() . ') center/cover no-repeat' : 'var(--brand)' }}; padding: 1.25rem; color: #fff; position: relative; border-bottom: 3px solid var(--accent);">
                    
                    {{-- Blue Gradient Fade (Bottom to Middle) --}}
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, var(--brand) 0%, rgba(15, 23, 42, 0.4) 50%, rgba(15, 23, 42, 0) 100%); z-index: 1;"></div>

                    <div style="position: relative; z-index: 2;">
                        <h3 style="font-size: 1.4rem; font-weight: 800; margin-bottom: 0.2rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; letter-spacing: -0.02em;">
                            <a href="#" style="color: #fff; text-decoration: none;">{{ $room->subject_name }}</a>
                        </h3>
                        <p style="font-size: 0.85rem; font-weight: 600; color: var(--accent);">{{ $room->tutor?->name ?? 'Unassigned' }}</p>
                    </div>
                    
                    {{-- Teacher Avatar with Accent Border --}}
                    <img src="{{ $room->tutor?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($room->tutor?->name ?? 'N/A') . '&background=0f172a&color=fff' }}" 
                         style="width: 70px; height: 70px; border-radius: 50%; border: 3px solid var(--accent); position: absolute; right: 1.25rem; bottom: -35px; z-index: 3; object-fit: cover; background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                </div>

                {{-- Card Body --}}
                <div style="padding: 1.5rem 1.25rem; flex-grow: 1; min-height: 90px;">
                    <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-weight: 500;">
                        {{ $room->description ?? 'No classroom description available.' }}
                    </p>
                </div>

                {{-- Action Footer with Motif Buttons --}}
                <div style="padding: 0.75rem 1.25rem; border-top: 1px solid var(--brand-soft); display: flex; justify-content: space-between; align-items: center; background: #fff;">
                    @if(auth()->check())
                        @php
                            // is_member is set by allJoinedRooms()/allTaughtRooms() for cross-db rooms.
                            // Fallback to tutor_id match or admin check — never call students relation
                            // on cross-DB hydrated models as it will query the wrong database.
                            $isMember = $room->is_member
                                     ?? ($room->tutor_id === auth()->id())
                                     || auth()->user()->isAdmin();
                        @endphp

                        @php
                            $roomOrgSlug = $room->org_slug ?? (isset($org) ? $org->slug : null);
                            if ($roomOrgSlug) {
                                $archiveRoute = isset($org) ? route('org.rooms.archive', $room->id) : route('rooms.tenant-archive', ['room' => $room->id, 'org_slug' => $roomOrgSlug]);
                            } else {
                                $archiveRoute = route('rooms.archive', $room->id);
                            }
                        @endphp
                        @if(auth()->id() === $room->tutor_id)
                            <form action="{{ $archiveRoute }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="color: #64748b; font-weight: 700; font-size: 0.75rem; padding: 0.5rem 0.75rem; text-decoration: none; border-radius: 0.5rem; border: 1px solid var(--border); background: transparent; cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em; transition: 0.2s;" onmouseover="this.style.background='#fee2e2'; this.style.color='#b91c1c'; this.style.borderColor='#fecaca';" onmouseout="this.style.background='transparent'; this.style.color='#64748b'; this.style.borderColor='var(--border)';">
                                    Archive
                                </button>
                            </form>
                        @else
                            <div></div> {{-- Spacer if not tutor --}}
                        @endif
                        
                        @php
                            $roomOrgSlug = $room->org_slug ?? (isset($org) ? $org->slug : null);
                            if ($roomOrgSlug) {
                                $showRoute = route('rooms.enter', ['room' => $room->id, 'org_slug' => $roomOrgSlug]);
                                // We use the central join route to join, which requires no tenant context
                                $joinRoute = route('rooms.join', $room->id);
                            } else {
                                $showRoute = route('rooms.show', $room->id);
                                $joinRoute = route('rooms.show', $room->id); // central students self-join via show page
                            }
                        @endphp
                        
                        @if($isMember)
                            <a href="{{ $showRoute }}" style="color: var(--brand); background: var(--accent); font-weight: 700; font-size: 0.8rem; padding: 0.5rem 1.5rem; text-decoration: none; border-radius: 0.5rem; transition: 0.2s; text-transform: uppercase; letter-spacing: 0.05em; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);" onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-1px)';" onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)';" >
                                Enter
                            </a>
                        @elseif($joinRoute)
                            <form action="{{ $joinRoute }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="color: #fff; background: var(--brand); font-weight: 700; font-size: 0.8rem; padding: 0.5rem 1.5rem; border: none; border-radius: 0.5rem; cursor: pointer; transition: 0.2s; text-transform: uppercase; letter-spacing: 0.05em; box-shadow: 0 2px 4px rgba(15, 23, 42, 0.2);" onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-1px)';" onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)';" >
                                    Join
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" style="color: var(--brand); font-weight: 800; font-size: 0.8rem; text-decoration: none; text-transform: uppercase; width: 100%; text-align: center;">Login to Access</a>
                    @endif
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; background: white; border: 2px dashed var(--border); border-radius: 1.25rem; padding: 4rem; text-align: center;">
                <img src="{{ asset('images/room-invisible.png') }}" alt="No Classrooms" style="width: 200px; margin-bottom: 1.5rem; opacity: 0.8;">
                <p style="color: var(--text-muted); font-size: 1.1rem; font-weight: 600;">No classrooms available at the moment.</p>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 0.5rem;">Check back later for new learning opportunities.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
