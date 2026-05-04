@extends('layouts.app')

@section('title', 'Archived Classrooms - LinkLearn')

@section('content')
<div style="width: 100%; max-width: 1100px;">
    <div style="margin-bottom: 3rem;">
        <h1 style="font-size: 2.25rem; font-weight: 800; letter-spacing: -0.04em; color: var(--brand); margin-bottom: 0.5rem;">
            Archived Classrooms
        </h1>
        <p style="color: var(--text-muted); font-weight: 600;">Classrooms that are currently hidden from your main dashboard.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
        @php
            $archivedOrgs = \App\Models\Organization::where('user_id', auth()->id())
                                                    ->where('status', 'archived')
                                                    ->get();
        @endphp

        @foreach($archivedOrgs as $org)
        <div style="background: #fff; border: 1px solid var(--border); border-radius: 1.25rem; overflow: hidden; opacity: 0.8; transition: 0.3s; filter: grayscale(0.5);" onmouseover="this.style.opacity='1'; this.style.filter='none'" onmouseout="this.style.opacity='0.8'; this.style.filter='grayscale(0.5)'">
            <div style="height: 100px; background: #64748b; padding: 1.5rem; position: relative;">
                <h3 style="color: #fff; font-size: 1.1rem; font-weight: 800;">{{ $org->name }}</h3>
                <p style="color: rgba(255,255,255,0.6); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Archived</p>
            </div>
            
            <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">
                    This classroom is inactive. Restore it to manage materials again.
                </p>
                <form action="{{ route('org.unarchive', $org->slug) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 0.85rem;">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.001 0 01-15.357-2m15.357 2H15"></path></svg>
                        Restore Classroom
                    </button>
                </form>
            </div>
        </div>
        @endforeach

        @if($archivedOrgs->isEmpty())
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; border: 2px dashed var(--border); border-radius: 1.5rem;">
                <p style="color: var(--text-muted); font-weight: 600;">No archived classrooms found.</p>
            </div>
        @endif
    </div>
</div>
@endsection
