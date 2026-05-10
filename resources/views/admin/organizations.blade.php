@extends('layouts.app')

@section('title', 'Tenants Management - LinkLearn')

@section('content')
<div style="width: 100%; max-width: 1200px;">
    <div style="margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--brand); letter-spacing: -0.04em; margin-bottom: 0.5rem;">Tenants Management</h1>
        <p style="color: var(--text-muted); font-weight: 600;">Manage all organizations/tenants on the platform.</p>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 1rem; border-radius: 0.75rem; margin-bottom: 2rem; border: 1px solid #a7f3d0; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border: 1px solid var(--border); border-radius: 1rem; overflow: hidden; box-shadow: var(--shadow);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--surface); border-bottom: 1px solid var(--border);">
                    <th style="padding: 1.25rem 2rem; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">Organization Name</th>
                    <th style="padding: 1.25rem 2rem; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">Owner</th>
                    <th style="padding: 1.25rem 2rem; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">Register Date</th>
                    <th style="padding: 1.25rem 2rem; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">Status</th>
                    <th style="padding: 1.25rem 2rem; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($organizations as $org)
                <tr style="border-bottom: 1px solid var(--brand-soft); transition: 0.2s;" onmouseover="this.style.background='var(--brand-soft)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.5rem 2rem;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-weight: 800; color: var(--brand); font-size: 1rem;">{{ $org->name }}</span>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $org->slug }}</span>
                        </div>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <span style="font-weight: 600; color: var(--text-main);">{{ optional($org->owner)->name ?? 'Deleted User' }}</span>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <span style="color: var(--text-muted); font-size: 0.9rem;">{{ $org->created_at->format('M d, Y') }}</span>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <span style="white-space: nowrap; background: {{ $org->status === 'active' ? '#dcfce7' : ($org->status === 'pending_approval' ? '#fef3c7' : '#fee2e2') }}; color: {{ $org->status === 'active' ? '#15803d' : ($org->status === 'pending_approval' ? '#92400e' : '#b91c1c') }}; padding: 0.4rem 1rem; border-radius: 999px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">
                            {{ str_replace('_', ' ', $org->status) }}
                        </span>
                    </td>
                    <td style="padding: 1.5rem 2rem; text-align: right;">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                            @if($org->status === 'pending_approval')
                                @if($org->proof_of_payment)
                                    <a href="{{ route('admin.proofs.view', basename($org->proof_of_payment)) }}" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.75rem; white-space: nowrap;">View Proof</a>
                                @endif
                                <form action="{{ route('admin.org.approve', $org->slug) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn btn-accent" style="padding: 0.5rem 1rem; font-size: 0.75rem; color: var(--brand); white-space: nowrap;">
                                        Approve
                                    </button>
                                </form>
                        @elseif($org->status === 'pending_payment')
                            <button class="btn btn-outline" disabled style="padding: 0.5rem 1rem; font-size: 0.75rem; color: var(--text-muted); border-color: var(--border);">
                                Awaiting Payment
                            </button>
                        @else
                            @if($org->status === 'active')
                                <button onclick="document.getElementById('disable-modal-{{ $org->slug }}').style.display='flex'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.75rem; border-color: #ef4444; color: #ef4444;">
                                    Disable
                                </button>

                                {{-- Disable Reason Modal --}}
                                <div id="disable-modal-{{ $org->slug }}" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; text-align: left;">
                                    <div style="background: #fff; width: 100%; max-width: 400px; border-radius: 1rem; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,.25);">
                                        <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                                            <h3 style="font-weight: 800; color: var(--brand); margin: 0; font-size: 1.1rem;">Disable Organization</h3>
                                            <button onclick="document.getElementById('disable-modal-{{ $org->slug }}').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
                                        </div>
                                        <form action="{{ route('admin.org.toggle', $org->slug) }}" method="POST" style="padding: 1.5rem;">
                                            @csrf
                                            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">Select the reason for disabling <strong>{{ $org->name }}</strong>.</p>
                                            
                                            <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.5rem;">
                                                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; cursor: pointer;">
                                                    <input type="radio" name="disable_reason" value="payment" required>
                                                    Disabled due to missing monthly payment
                                                </label>
                                                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; cursor: pointer;">
                                                    <input type="radio" name="disable_reason" value="issue" required>
                                                    Temporarily disabled for some issue
                                                </label>
                                            </div>

                                            <div style="display: flex; gap: 0.75rem;">
                                                <button type="button" onclick="document.getElementById('disable-modal-{{ $org->slug }}').style.display='none'" class="btn btn-outline" style="flex: 1;">Cancel</button>
                                                <button type="submit" class="btn" style="flex: 1; background: #ef4444; color: white;">Confirm Disable</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('admin.org.toggle', $org->slug) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-accent" style="padding: 0.5rem 1rem; font-size: 0.75rem; color: var(--brand);">
                                        Enable
                                    </button>
                                </form>
                            @endif
                        @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
