@extends('layouts.app')

@section('title', 'Users - LinkLearn')

@section('content')
<div style="width: 100%; max-width: 1200px;">
    <div style="margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--brand); letter-spacing: -0.04em; margin-bottom: 0.5rem;">Users</h1>
        <p style="color: var(--text-muted); font-weight: 600;">Full overview of all platform members.</p>
    </div>

    {{-- Teachers Table --}}
    <div style="margin-bottom: 4rem;">
        <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--brand); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
            Teachers <span style="background: var(--brand-soft); color: var(--brand); padding: 0.2rem 0.6rem; border-radius: 99px; font-size: 0.85rem;">{{ $teachers->count() }}</span>
        </h3>
        <div style="background: white; border: 1px solid var(--border); border-radius: 1rem; overflow: hidden; box-shadow: var(--shadow);">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: var(--surface); border-bottom: 1px solid var(--border);">
                        <th style="padding: 1rem 2rem; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted);">Name</th>
                        <th style="padding: 1rem 2rem; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted);">Organization</th>
                        <th style="padding: 1rem 2rem; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted);">Register Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                    <tr style="border-bottom: 1px solid var(--brand-soft);">
                        <td style="padding: 1.25rem 2rem; font-weight: 700; color: var(--brand);">{{ $teacher->name }}</td>
                        <td style="padding: 1.25rem 2rem; color: var(--text-main);">
                            @if(isset($teacher->all_organizations) && $teacher->all_organizations->isNotEmpty())
                                {{ $teacher->all_organizations->pluck('name')->implode(', ') }}
                            @else
                                <span style="color: var(--text-muted); font-style: italic;">No Organization</span>
                            @endif
                        </td>
                        <td style="padding: 1.25rem 2rem; color: var(--text-muted);">{{ $teacher->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="padding: 2rem; text-align: center; color: var(--text-muted);">No teachers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Students Table --}}
    <div>
        <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--brand); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
            Students <span style="background: var(--brand-soft); color: var(--brand); padding: 0.2rem 0.6rem; border-radius: 99px; font-size: 0.85rem;">{{ $students->count() }}</span>
        </h3>
        <div style="background: white; border: 1px solid var(--border); border-radius: 1rem; overflow: hidden; box-shadow: var(--shadow);">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: var(--surface); border-bottom: 1px solid var(--border);">
                        <th style="padding: 1rem 2rem; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted);">Name</th>
                        <th style="padding: 1rem 2rem; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted);">Organization</th>
                        <th style="padding: 1rem 2rem; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted);">Register Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr style="border-bottom: 1px solid var(--brand-soft);">
                        <td style="padding: 1.25rem 2rem; font-weight: 700; color: var(--brand);">{{ $student->name }}</td>
                        <td style="padding: 1.25rem 2rem; color: var(--text-main);">
                            @if(isset($student->all_organizations) && $student->all_organizations->isNotEmpty())
                                {{ $student->all_organizations->pluck('name')->implode(', ') }}
                            @else
                                <span style="color: var(--text-muted); font-style: italic;">No Organization</span>
                            @endif
                        </td>
                        <td style="padding: 1.25rem 2rem; color: var(--text-muted);">{{ $student->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="padding: 2rem; text-align: center; color: var(--text-muted);">No students found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
