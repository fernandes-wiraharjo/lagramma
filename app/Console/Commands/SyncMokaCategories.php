<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncMokaCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-moka-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync master product category from MOKA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $baseUrl = env('MOKA_API_URL');
        $token = getMokaToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($baseUrl . '/categories');

        if ($response->successful()) {
            Log::info('Sync MOKA Product Categories successfully.', $categories);
            $categories = $response->json();
            //save data to db here...
        } else {
            $status = $response->status();

            // If token expired, refresh it and retry
            if ($status === 401) {
                $newToken = refreshMokaToken();
                if ($newToken) {
                    Log::info('Token refreshed. Retrying Sync MOKA Product Categories...');
                    $this->handle(); // Retry fetching categories
                }
            } else {
                Log::error("Sync MOKA Product Categories API Error: HTTP {$status}");
                // Log the error in the database
                insertApiErrorLog('Sync MOKA Product Categories', $baseUrl . '/categories', 'GET', null, null, null, $status, $response->body());
            }
        }
    }
}
