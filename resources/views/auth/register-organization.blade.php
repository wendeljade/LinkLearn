@extends('layouts.app')

@section('title', 'Register Organization')

@section('content')
<div class="card-modern" style="max-width: 600px; text-align: left; padding: 2.5rem;">
    <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--brand); margin-bottom: 0.5rem; letter-spacing: -0.03em;">
        Register Organization
    </h1>
    <p style="color: var(--text-muted); margin-bottom: 2rem; font-weight: 500;">Fill in the details to setup your organization's workspace.</p>

    <form action="/register-organization" method="POST" enctype="multipart/form-data" id="register-form" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf
        
        {{-- STEP 1: Details --}}
        <div id="step-1" style="display: flex; flex-direction: column; gap: 1.5rem;">
            {{-- Org Name --}}
            <div>
                <label style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Organization / School Name</label>
                <input type="text" id="org_name" name="name" placeholder="e.g. Bukidnon State University" 
                       style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem;" required>
            </div>

            {{-- Slug --}}
            <div>
                <label style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">URL Link (Slug)</label>
                <input type="text" id="org_slug" name="slug" placeholder="advanced-math" 
                       style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem; background: var(--brand-soft);" required>
            </div>

            {{-- Description --}}
            <div>
                <label style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Description</label>
                <textarea name="description" rows="3" placeholder="Briefly describe your organization..." 
                          style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem; resize: none;"></textarea>
            </div>

            {{-- Cover Photo --}}
            <div>
                <label style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Cover Photo</label>
                <div style="position: relative; border: 2px dashed var(--border); border-radius: 0.75rem; padding: 1.5rem; text-align: center; transition: 0.2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                    <input type="file" name="cover_photo" accept="image/*" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    <div style="color: var(--text-muted);">
                        <svg style="width: 2rem; height: 2rem; margin-bottom: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        <p style="font-size: 0.85rem; font-weight: 600;">Click to upload or drag and drop</p>
                        <p style="font-size: 0.75rem;">PNG, JPG up to 5MB</p>
                    </div>
                </div>
            </div>

            <button type="button" id="btn-next" class="btn btn-accent" style="width: 100%; padding: 1rem; margin-top: 0.5rem;">Proceed to Payment (₱999)</button>
        </div>

        {{-- STEP 2: Payment --}}
        <div id="step-2" style="display: none; flex-direction: column; gap: 1.5rem;">
            <div style="background: #f8fafc; border-radius: 1rem; border: 1px solid var(--border); padding: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1rem; color: var(--brand);">Subscription Payment</h2>
                <p style="margin-bottom: 0.75rem; color: var(--text-muted);">Amount due: <strong style="font-size: 1.25rem; color: #0f172a;">₱999</strong></p>
                <p style="margin-bottom: 0.25rem; color: var(--text-muted);">Payment method: Bank transfer or online payment.</p>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Upload proof of payment once you have completed the transaction. Your organization will be submitted for super admin approval.</p>

                <div style="margin-top: 1.5rem;">
                    <label style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand); display: block; margin-bottom: 0.5rem;">Proof of Payment <span style="color:red">*</span></label>
                    <div style="position: relative; border: 2px dashed var(--border); border-radius: 0.75rem; padding: 1.5rem; text-align: center; background: white; transition: 0.2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                        <input type="file" id="proof_of_payment" name="proof_of_payment" accept="image/*,.pdf" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                        <div style="color: var(--text-muted);">
                            <svg style="width: 2rem; height: 2rem; margin-bottom: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            <p style="font-size: 0.85rem; font-weight: 600;">Click to upload proof of payment</p>
                            <p style="font-size: 0.75rem;">PNG, JPG, PDF up to 5MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="button" id="btn-back" class="btn btn-outline" style="flex: 1; padding: 1rem;">Back</button>
                <button type="button" id="btn-submit" class="btn btn-accent" style="flex: 2; padding: 1rem;">Submit & Register Organization</button>
            </div>
        </div>
    </form>
</div>

<script>
    const orgName = document.getElementById('org_name');
    const orgSlug = document.getElementById('org_slug');
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const btnNext = document.getElementById('btn-next');
    const btnBack = document.getElementById('btn-back');
    const btnSubmit = document.getElementById('btn-submit');
    const form = document.getElementById('register-form');
    const proofInput = document.getElementById('proof_of_payment');

    orgName.addEventListener('input', function() {
        let name = this.value;
        let slug = name.toLowerCase()
                       .trim()
                       .replace(/[^\w\s-]/g, '')
                       .replace(/[\s_-]+/g, '-')
                       .replace(/^-+|-+$/g, '');
        orgSlug.value = slug;
    });

    btnNext.addEventListener('click', function() {
        if (!orgName.value || !orgSlug.value) {
            alert('Please fill out the Organization Name and Slug.');
            return;
        }
        step1.style.display = 'none';
        step2.style.display = 'flex';
    });

    btnBack.addEventListener('click', function() {
        step2.style.display = 'none';
        step1.style.display = 'flex';
    });

    btnSubmit.addEventListener('click', function() {
        if (!proofInput.files.length) {
            alert('Please upload your proof of payment before submitting.');
            return;
        }
        form.submit();
    });
</script>
@endsection
