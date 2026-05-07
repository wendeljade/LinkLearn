<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GitHubReleaseService
{
    /**
     * Get the latest GitHub release version.
     * Caches the result for 1 minute to prevent API rate limiting.
     *
     * @return string
     */
    public function getLatestVersion(): string
    {
        return Cache::remember('github_latest_release_version', now()->addMinutes(1), function () {
            $token = config('services.github.token');
            $repo = config('services.github.repo');

            if (!$token || !$repo) {
                return 'v1.0.0';
            }

            try {
                $response = Http::withToken($token)
                    ->timeout(5)
                    ->get("https://api.github.com/repos/{$repo}/releases/latest");

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['tag_name'] ?? 'v1.0.0';
                }

                // If 404, there are no releases yet.
                // If other error, we just return default.
                if ($response->status() !== 404) {
                    Log::warning("GitHub Release API returned status: " . $response->status());
                }

                return 'v1.0.0';
            } catch (\Exception $e) {
                Log::error("Failed to fetch GitHub release: " . $e->getMessage());
                return 'v1.0.0';
            }
        });
    }
}
