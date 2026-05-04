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
        Route::get('/dashboard', [\App\Http\Controllers\OrganizationController::class, 'dashboard'])->name('org.admin.dashboard');
        Route::get('/settings', [\App\Http\Controllers\OrganizationController::class, 'edit'])->name('org.settings');
        Route::post('/settings', [\App\Http\Controllers\OrganizationController::class, 'update'])->name('org.update');
        Route::get('/rooms', [\App\Http\Controllers\RoomController::class, 'index'])->name('org.rooms.index');
        Route::get('/rooms/create', [\App\Http\Controllers\RoomController::class, 'create'])->middleware(['role:admin,org_admin,teacher,tutor'])->name('org.rooms.create');
        Route::post('/rooms', [\App\Http\Controllers\RoomController::class, 'store'])->middleware(['role:admin,org_admin,teacher,tutor'])->name('org.rooms.store');
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
        Route::post('/activities/{activity}/submit', [\App\Http\Controllers\RoomController::class, 'submitActivity'])->name('org.rooms.activities.submit');
        Route::post('/submissions/{submission}/grade', [\App\Http\Controllers\RoomController::class, 'gradeSubmission'])->name('org.rooms.activities.grade');
        Route::post('/rooms/{room}/archive', [\App\Http\Controllers\RoomController::class, 'archive'])->name('org.rooms.archive');
        Route::post('/rooms/{room}/unarchive', [\App\Http\Controllers\RoomController::class, 'unarchive'])->name('org.rooms.unarchive');
        Route::post('/rooms/{room}/join', [\App\Http\Controllers\RoomController::class, 'join'])->name('org.rooms.join');
        Route::get('/team', [\App\Http\Controllers\OrganizationController::class, 'team'])->name('org.team');
        Route::post('/team/invite', [\App\Http\Controllers\OrganizationController::class, 'invite'])->name('org.team.invite');
        Route::get('/archived', [\App\Http\Controllers\RoomController::class, 'archived'])->name('org.rooms.archived');
        
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
