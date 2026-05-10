<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
*/

// Wrap routes to only apply on subdomains, preventing them from overriding central routes
foreach (config('tenancy.central_domains') as $centralDomain) {
    Route::domain('{tenant}.' . $centralDomain)
        ->middleware([
            'web',
            InitializeTenancyBySubdomain::class,
            PreventAccessFromCentralDomains::class,
            \App\Http\Middleware\SetTenantUrlDefault::class,
        ])->group(function () {
    
    Route::get('/', function () {
        return view('tenant.landing', [
            'org' => tenant(),
            'org_slug' => tenant('slug')
        ]);
    })->name('org.landing');

    // Redirect /login on subdomain to central login
    // This prevents the 419 CSRF error from users trying to login on sti.localhost directly.
    Route::get('/login', function () {
        $centralDomain = config('tenancy.central_domains')[0] ?? 'localhost';
        $port = request()->getPort();
        $portStr = ($port && $port != 80 && $port != 443) ? ':' . $port : '';
        return redirect(request()->getScheme() . '://' . $centralDomain . $portStr . '/login');
    });

    Route::get('/magic-login/{token}', [\App\Http\Controllers\OrganizationController::class, 'magicLogin'])->name('org.magic.login');

    Route::middleware(['auth'])->group(function () {
        // Notifications
        Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('org.notifications.index');
        Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('org.notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('org.notifications.read-all');
        Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('org.notifications.destroy');

        Route::get('/dashboard', [\App\Http\Controllers\OrganizationController::class, 'dashboard'])->name('org.admin.dashboard');
        Route::get('/settings', [\App\Http\Controllers\OrganizationController::class, 'edit'])->name('org.settings');
        // Auth Routes
        Route::post('/logout', [\App\Http\Controllers\OrganizationController::class, 'logout'])->name('org.logout');

        // System Update Acknowledgment
        Route::get('/system-update', [\App\Http\Controllers\AdminController::class, 'acknowledgeUpdate'])->name('org.system.update');
        Route::post('/settings', [\App\Http\Controllers\OrganizationController::class, 'update'])->name('org.update');
        Route::get('/rooms', [\App\Http\Controllers\RoomController::class, 'index'])->name('org.rooms.index');
        Route::get('/rooms/create', [\App\Http\Controllers\RoomController::class, 'create'])->middleware(['role:admin,org_admin'])->name('org.rooms.create');
        Route::post('/rooms', [\App\Http\Controllers\RoomController::class, 'store'])->middleware(['role:admin,org_admin'])->name('org.rooms.store');
        Route::get('/rooms/{room}', [\App\Http\Controllers\RoomController::class, 'show'])->name('org.rooms.show');
        Route::put('/rooms/{room}', [\App\Http\Controllers\RoomController::class, 'update'])->name('org.rooms.update');
        Route::post('/rooms/{room}/invite', [\App\Http\Controllers\RoomController::class, 'inviteStudent'])->name('org.rooms.invite');
        Route::post('/rooms/{room}/invite-teacher', [\App\Http\Controllers\RoomController::class, 'inviteTeacher'])->name('org.rooms.invite-teacher');
        Route::post('/rooms/{room}/upload-file', [\App\Http\Controllers\RoomController::class, 'uploadFile'])->name('org.rooms.upload-file');
        Route::post('/rooms/{room}/files/{file}/purchase', [\App\Http\Controllers\RoomController::class, 'purchaseFile'])->name('org.rooms.purchase-file');
        Route::get('/rooms/files/{file}/preview', [\App\Http\Controllers\RoomController::class, 'previewFileOrg'])->name('org.rooms.preview-file');
        Route::get('/rooms/files/{file}/download', [\App\Http\Controllers\RoomController::class, 'downloadFileOrg'])->name('org.rooms.download-file');
        Route::post('/rooms/{room}/purchases/{purchase}/approve', [\App\Http\Controllers\RoomController::class, 'approvePurchase'])->name('org.rooms.approve-purchase');

        // Route to serve proof of payment files securely
        Route::get('/tenant-proof/{path}', [\App\Http\Controllers\RoomController::class, 'serveTenantProofOrg'])
            ->where('path', '.*')
            ->name('org.rooms.tenant-proof');
        
        Route::post('/rooms/{room}/activities', [\App\Http\Controllers\RoomController::class, 'storeActivity'])->name('org.rooms.activities.store');
        Route::put('/activities/{activity}', [\App\Http\Controllers\RoomController::class, 'updateActivity'])->name('org.rooms.activities.update');
        
        // Announcements
        Route::post('/rooms/{room}/announcements', [\App\Http\Controllers\RoomController::class, 'storeAnnouncement'])->name('org.rooms.announcements.store');
        Route::put('/announcements/{announcement}', [\App\Http\Controllers\RoomController::class, 'updateAnnouncement'])->name('org.rooms.announcements.update');
        Route::delete('/announcements/{announcement}', [\App\Http\Controllers\RoomController::class, 'destroyAnnouncement'])->name('org.rooms.announcements.destroy');
        Route::get('/activities/{activity}/attachment', [\App\Http\Controllers\RoomController::class, 'downloadActivityAttachmentOrg'])->name('org.rooms.activities.attachment');
        Route::delete('/activities/{activity}', [\App\Http\Controllers\RoomController::class, 'destroyActivity'])->name('org.rooms.activities.destroy');
        Route::post('/activities/{activity}/submit', [\App\Http\Controllers\RoomController::class, 'submitActivity'])->name('org.rooms.activities.submit');
        Route::post('/submissions/{submission}/grade', [\App\Http\Controllers\RoomController::class, 'gradeSubmission'])->name('org.rooms.activities.grade');
        Route::post('/rooms/{room}/archive', [\App\Http\Controllers\RoomController::class, 'archive'])->name('org.rooms.archive');
        Route::post('/rooms/{room}/unarchive', [\App\Http\Controllers\RoomController::class, 'unarchive'])->name('org.rooms.unarchive');
        Route::post('/rooms/{room}/join', [\App\Http\Controllers\RoomController::class, 'join'])->name('org.rooms.join');
        Route::post('/rooms/{room}/approve-student/{student}', [\App\Http\Controllers\RoomController::class, 'approveJoin'])->name('org.rooms.approve-student');
        Route::post('/rooms/{room}/reject-student/{student}', [\App\Http\Controllers\RoomController::class, 'rejectJoin'])->name('org.rooms.reject-student');
        Route::delete('/rooms/{room}/remove-student/{student}', [\App\Http\Controllers\RoomController::class, 'removeStudent'])->name('org.rooms.remove-student');
        Route::get('/team', [\App\Http\Controllers\OrganizationController::class, 'team'])->name('org.team');
        Route::post('/team/invite', [\App\Http\Controllers\OrganizationController::class, 'invite'])->name('org.team.invite');
        Route::get('/archived', [\App\Http\Controllers\RoomController::class, 'archived'])->name('org.rooms.archived');
        
        // Tenant Support Routes
        Route::get('/support', [\App\Http\Controllers\SupportTicketController::class, 'index'])->name('org.support.index');
        Route::post('/support', [\App\Http\Controllers\SupportTicketController::class, 'store'])->name('org.support.store');
        Route::get('/support/{id}', [\App\Http\Controllers\SupportTicketController::class, 'show'])->name('org.support.show');
        Route::post('/support/{id}/reply', [\App\Http\Controllers\SupportTicketController::class, 'reply'])->name('org.support.reply');

        // GCash QR Code
        Route::post('/gcash-qr', [\App\Http\Controllers\OrganizationController::class, 'uploadGcashQr'])->name('org.gcash.upload');

        // Tenant Logout: Clear tenant session, then redirect to central logout to clear central session
        Route::post('/logout', function (\Illuminate\Http\Request $request) {
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            $centralDomain = config('tenancy.central_domains')[0] ?? 'localhost';
            $port = request()->getPort();
            $portStr = ($port && $port != 80 && $port != 443) ? ':' . $port : '';
            return redirect(request()->getScheme() . '://' . $centralDomain . $portStr . '/central-logout');
        })->name('org.logout');
    });
});
}
