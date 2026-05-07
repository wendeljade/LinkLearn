<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC & AUTH ROUTES
|--------------------------------------------------------------------------
*/

// Main Landing Page (Redirect to Dashboard if Auth, show Hero if Guest)
foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('landing');
})->name('landing');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () { 
        return response()->view('auth.login')->withHeaders([
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma' => 'no-cache',
        ]); 
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Google Authentication
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Central Logout (used for cross-domain logout redirection from tenant subdomains)
Route::get('/central-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
});

// Support Ticketing Routes (Central)
Route::middleware(['auth'])->group(function () {
    Route::get('/support', [\App\Http\Controllers\SupportTicketController::class, 'index'])->name('support.index');
    Route::post('/support', [\App\Http\Controllers\SupportTicketController::class, 'store'])->name('support.store');
    Route::get('/support/{id}', [\App\Http\Controllers\SupportTicketController::class, 'show'])->name('support.show');
    Route::post('/support/{id}/reply', [\App\Http\Controllers\SupportTicketController::class, 'reply'])->name('support.reply');
    
    // System Update Acknowledgment (Accessible to all users)
    Route::get('/system-update', [\App\Http\Controllers\AdminController::class, 'acknowledgeUpdate'])->name('system.update');
});

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
    Route::get('/', function () { return redirect()->route('admin.dashboard'); });

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/organizations', [AdminController::class, 'organizations'])->name('admin.organizations');
    Route::post('/organizations/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admin.org.toggle');
    Route::post('/organizations/{id}/approve', [AdminController::class, 'approve'])->name('admin.org.approve');
    Route::get('/monitoring', [AdminController::class, 'monitoring'])->name('admin.monitoring');
    Route::get('/proofs/{filename}', [AdminController::class, 'viewProof'])->name('admin.proofs.view');

    // Admin Support Management
    Route::get('/support', [AdminController::class, 'supportTickets'])->name('admin.support.index');
    Route::get('/support/{id}', [AdminController::class, 'viewSupportTicket'])->name('admin.support.show');
    Route::post('/support/{id}/reply', [AdminController::class, 'replySupportTicket'])->name('admin.support.reply');
});

/*
|--------------------------------------------------------------------------
| 2. ORGANIZATION MANAGEMENT (Owners Only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/register-organization', [OrganizationController::class, 'create'])->name('register.org');
    Route::post('/register-organization', [OrganizationController::class, 'store'])->name('register.org.store');
    Route::get('/register-organization/payment/{org_slug}', [OrganizationController::class, 'payment'])->middleware(['auth'])->name('register.org.payment');
    Route::post('/register-organization/payment/{org_slug}', [OrganizationController::class, 'processPayment'])->middleware(['auth'])->name('register.org.payment.process');
    Route::get('/org/{org_slug}/subscription/payment', [OrganizationController::class, 'payment'])->middleware(['auth'])->name('org.subscription.payment');
    Route::post('/org/{org_slug}/subscription/payment', [OrganizationController::class, 'processPayment'])->middleware(['auth'])->name('org.subscription.payment.process');
    
    Route::post('/org/{id}/archive', function ($id) {
        $org = \App\Models\Organization::where('slug', $id)->firstOrFail();
        if ($org->user_id !== auth()->id()) abort(403);
        $org->update(['status' => 'archived']);
        return back();
    })->name('org.archive');

    Route::post('/org/{id}/unarchive', function ($id) {
        $org = \App\Models\Organization::where('slug', $id)->firstOrFail();
        if ($org->user_id !== auth()->id()) abort(403);
        $org->update(['status' => 'active']);
        return back();
    })->name('org.unarchive');

    Route::get('/archived', [RoomController::class, 'archived'])->name('rooms.archived');
    Route::post('/rooms/{room}/archive', [RoomController::class, 'archive'])->name('rooms.archive');
    Route::post('/rooms/{room}/unarchive', [RoomController::class, 'unarchive'])->name('rooms.unarchive');

    // Central cross-tenant archiving
    Route::post('/rooms/{room}/archive/{org_slug}', [RoomController::class, 'archiveTenant'])->name('rooms.tenant-archive');
    Route::post('/rooms/{room}/unarchive/{org_slug}', [RoomController::class, 'unarchiveTenant'])->name('rooms.tenant-unarchive');
});


/*
|--------------------------------------------------------------------------
| 3. MULTI-TENANT ROUTES (The Core Logic)
|--------------------------------------------------------------------------
|
| Tenant routes are now defined in routes/tenant.php and are executed in
| tenant context. The central application remains in routes/web.php.
|
*/

/*
|--------------------------------------------------------------------------
| 4. GLOBAL ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/create', [RoomController::class, 'create'])->middleware(['role:admin,teacher,tutor'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->middleware(['role:admin,teacher,tutor'])->name('rooms.store');
    
    // Central entry route for seamless tenant redirection via magic login
    Route::get('/rooms/{room}/enter/{org_slug}', [RoomController::class, 'enterTenant'])->name('rooms.enter');
    
    Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
    Route::post('/rooms/{room}/join', [RoomController::class, 'join'])->name('rooms.join');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::post('/rooms/{room}/invite', [RoomController::class, 'inviteStudent'])->name('rooms.invite');
    Route::post('/rooms/{room}/invite-teacher', [RoomController::class, 'inviteTeacher'])->name('rooms.invite-teacher');
    Route::post('/rooms/{room}/upload-file', [RoomController::class, 'uploadFile'])->name('rooms.upload-file');
    Route::post('/rooms/{room}/files/{file}/purchase', [RoomController::class, 'purchaseFile'])->name('rooms.purchase-file');
    Route::get('/rooms/files/{file}/preview', [RoomController::class, 'previewFile'])->name('rooms.preview-file');
    Route::get('/rooms/files/{file}/download', [RoomController::class, 'downloadFile'])->name('rooms.download-file');
    Route::post('/rooms/{room}/purchases/{purchase}/approve', [RoomController::class, 'approvePurchase'])->name('rooms.approve-purchase');

    // Serve proof-of-payment files from tenant-isolated storage on the central domain.
    // The FilesystemTenancyBootstrapper stores files in storage/tenant{slug}/app/public/...
    // so asset('storage/...') on the central domain would point to the wrong location.
    Route::get('/tenant-proof/{org_slug}/{path}', [RoomController::class, 'serveTenantProof'])
        ->where('path', '.*')
        ->name('rooms.tenant-proof');

    // Activity and Submission Routes (Global)
    Route::post('/rooms/{room}/activities', [RoomController::class, 'storeActivity'])->name('rooms.activities.store');
    Route::get('/activities/{activity}/attachment', [RoomController::class, 'downloadActivityAttachment'])->name('rooms.activities.attachment');
    Route::delete('/activities/{activity}', [RoomController::class, 'destroyActivity'])->name('rooms.activities.destroy');
    Route::post('/activities/{activity}/submit', [RoomController::class, 'submitActivity'])->name('rooms.activities.submit');
    Route::post('/submissions/{submission}/grade', [RoomController::class, 'gradeSubmission'])->name('rooms.activities.grade');
});
    });
}
