@extends('layouts.app')

@section('title', 'Subscription Payment')

@section('content')
<div style="max-width: 680px; margin: 0 auto; padding: 3rem 1rem;">
    <div style="background: white; border-radius: 1.5rem; border: 1px solid var(--border); box-shadow: var(--shadow); padding: 2.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 800; color: var(--brand); margin-bottom: 0.25rem;">Subscription Payment</h1>
                <p style="color: var(--text-muted); font-weight: 600;">Your organization needs a valid subscription to access the tenant dashboard.</p>
            </div>
            <div style="text-align: right;">
                <span style="display: inline-block; padding: 0.75rem 1rem; border-radius: 999px; background: #f8fafc; color: #0f172a; font-weight: 700;">₱999 / month</span>
            </div>
        </div>

        <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem;">
            <p style="font-weight: 700; margin-bottom: 0.75rem;">{{ $org->name }}</p>
            <p style="color: var(--text-muted); margin-bottom: 0.5rem;">Subscription status: <strong>{{ ucfirst(str_replace('_', ' ', $org->status)) }}</strong></p>
            <p style="color: var(--text-muted); font-size: 0.95rem;">
                {{ $statusMessage ?? 'Pay ₱999 to activate your tenant subscription and continue to your admin dashboard.' }}
            </p>
        </div>

        @if(session('success'))
            <div style="background: #ecfdf5; color: #065f46; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; border: 1px solid #a7f3d0; font-weight: 600;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div style="background: #eff6ff; color: #1d4ed8; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; border: 1px solid #bfdbfe; font-weight: 600;">
                {{ session('info') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; border: 1px solid #fecaca; font-weight: 600;">
                {{ session('error') }}
            </div>
        @endif

        @php
            $paymentRoute = request()->routeIs('org.subscription.payment')
                ? route('org.subscription.payment.process', $org->slug)
                : route('register.org.payment.process', $org->slug);
        @endphp

        @if(in_array($org->status, ['pending_payment', 'deactive', 'expired']))
            <form action="{{ $paymentRoute }}" method="POST" enctype="multipart/form-data" style="display: grid; gap: 1.25rem;">
                @csrf
                <input type="hidden" name="payment_method" value="manual" />

                <div style="background: white; border-radius: 1rem; border: 1px solid var(--border); padding: 1.5rem;">
                    <h2 style="font-size: 1rem; font-weight: 800; margin-bottom: 1rem;">Payment details</h2>
                    <p style="margin-bottom: 0.75rem; color: var(--text-muted);">Amount due: <strong>₱999</strong></p>
                    <p style="margin-bottom: 0.25rem; color: var(--text-muted);">Payment method: Bank transfer or online payment.</p>
                    @if(file_exists(storage_path('app/public/admin/gcash_qr.png')))
                        <div style="margin: 1.5rem 0; text-align: center; background: var(--brand-soft); padding: 1.5rem; border-radius: 1rem; border: 1px dashed #00b4d8;">
                            <p style="font-size: 0.85rem; font-weight: 700; color: var(--brand); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">Scan to Pay via GCash</p>
                            @php
                                $centralDomains = config('tenancy.central_domains', ['localhost']);
                                $centralDomain  = $centralDomains[0];
                                $port = request()->getPort();
                                $portStr = ($port && $port != 80 && $port != 443) ? ':' . $port : '';
                                $qrUrl = request()->getScheme() . '://' . $centralDomain . $portStr . '/storage/admin/gcash_qr.png';
                            @endphp
                            <img src="{{ $qrUrl }}?v={{ time() }}" alt="Super Admin GCash QR" style="max-width: 250px; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);">
                        </div>
                    @endif
                    <p style="color: var(--text-muted); font-size: 0.95rem;">Upload proof of payment once you have completed the transaction. This will submit your payment for super admin review.</p>

                    <div style="margin-top: 1rem;">
                        <label style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Proof of Payment</label>
                        <div style="position: relative; border: 2px dashed var(--border); border-radius: 0.75rem; padding: 1.5rem; text-align: center; transition: 0.2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                            <input type="file" name="proof_of_payment" accept="image/*,.pdf" required style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    @error('proof_of_payment')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.75rem;">{{ $message }}</p>
                    @enderror
                            <div style="color: var(--text-muted);">
                                <svg style="width: 2rem; height: 2rem; margin-bottom: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                <p style="font-size: 0.85rem; font-weight: 600;">Click to upload proof of payment</p>
                                <p style="font-size: 0.75rem;">PNG, JPG, PDF up to 5MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-accent" style="width: 100%; padding: 1rem; font-size: 1rem;">Pay ₱999 and Continue</button>
                <a href="{{ route('register.org') }}" class="btn btn-outline" style="text-align: center; padding: 1rem;">Back to Registration</a>
            </form>
        @else
            <div style="background: white; border-radius: 1rem; border: 1px solid var(--border); padding: 1.5rem; display: grid; gap: 1rem;">
                @if($org->status === 'pending_approval')
                    <p style="font-size: 1rem; font-weight: 700; color: var(--brand);">Waiting for Super Admin Approval</p>
                    <p style="color: var(--text-muted);">Your payment has been submitted successfully. The organization will be activated once a super admin approves it.</p>
                @else
                    <p style="font-size: 1rem; font-weight: 700; color: var(--brand);">Action Required</p>
                    <p style="color: var(--text-muted);">This organization cannot proceed because it is not currently active. Please contact support or the platform administrator.</p>
                @endif
                <a href="{{ route('landing') }}" class="btn btn-outline" style="text-align: center; padding: 1rem;">Back to Home</a>
            </div>
        @endif
    </div>
</div>
@endsection
