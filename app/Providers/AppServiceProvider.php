<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\GitHubReleaseService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            $githubService = new GitHubReleaseService();
            $latestVersion = $githubService->getLatestVersion();
            
            View::share('appVersion', $latestVersion);
        } catch (\Exception $e) {
            View::share('appVersion', 'v1.0.0');
        }
    }
}
