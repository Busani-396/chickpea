<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use Illuminate\Support\Facades\DB;

class AnalyticsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:analytics-report {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generates analytics report for a given campaign';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        //$this->info("Testing :> Generating report for ID $id");
        $campaign = Campaign::with(['client'])->find($id);
        if (!$campaign){
            $this->error("Campaign with ID {$id} not found.");
            return;
        }

        $this->info("Generating Report for: {$campaign->name} (Client: {$campaign->client->name})");
        $this->newLine();

        $successCount = DB::table('campaign_data')->where('campaign_id', $id)->count();
        $duplicateCount = DB::table('duplicate_reports')->where('campaign_id', $id)->count();
        $totalHits = $successCount + $duplicateCount;
        $successRate = $totalHits > 0 ? round(($successCount / $totalHits) * 100, 2) : 0;

        // Flexible Custom Fields: The custom_fields object may contain arbitrary fields. The system must:
        // • Store them without requiring schema changes.
        // • Ensure they can be surfaced in analytics reports.

        $optionalFields = DB::table('campaign_data')
            ->where('campaign_id', $id)
            ->whereNotNull('custom_fields')
            ->limit(10)
            ->get();

           $discoveredKeys = $optionalFields->flatMap(function($item){
                return array_keys(json_decode($item->custom_fields, true) ?? []);
            })->unique()->implode(', ');

        $this->table(
            ['Indicator', 'Result'],
            [
                ['Total API Hits (Processed)', $totalHits],
                ['Successful Records', $successCount],
                ['Duplicate Attempts Logged', $duplicateCount],
                ['Ingestion Success Rate', $successRate . '%'],
                ['Custom Fields Detected', $discoveredKeys ?: 'None'],
            ]
        );

        if ($duplicateCount > 0) {
            $this->newLine();
            $this->warn("Recent Duplicate Conflicts:");
            $duplicates = DB::table('duplicate_reports')
                ->where('campaign_id', $id)
                ->latest()
                ->limit(5)
                ->get(['attempted_user_id', 'ip_address', 'created_at']);

            $this->table(
                ['User ID Attempted', 'Source IP', 'Timestamp'],
                $duplicates->map(fn($d) => (array)$d)->toArray()
            );
        }
    }
}
