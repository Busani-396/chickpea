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
        
        $campaign = Campaign::with(['client'])->find($id);

        if (!$campaign) {
            $this->error("Campaign with ID {$id} not found.");
            return;
        }

        $stats = DB::table('campaign_data')
            ->selectRaw('count(*) as success_count')
            ->where('campaign_id', $id)
            ->first();

        $dupStats = DB::table('duplicate_reports')
            ->selectRaw('count(*) as duplicate_count, MAX(created_at) as last_collision')
            ->where('campaign_id', $id)
            ->first();

        $successCount = $stats->success_count;
        $duplicateCount = $dupStats->duplicate_count;
        $totalHits = $successCount + $duplicateCount;
        $successRate = $totalHits > 0 ? round(($successCount / $totalHits) * 100, 2) : 0;

        $this->info("=== Campaign Analytics Report ===");
        $this->line("Campaign: <comment>{$campaign->name}</comment> | Client: <comment>{$campaign->client->name}</comment>");
        $this->line("Started On: <comment>{$campaign->start_date}</comment> to <comment>" . ($campaign->end_date ?? 'Ongoing') . "</comment>");
        $this->newLine();

        $sampleData = DB::table('campaign_data')
            ->where('campaign_id', $id)
            ->whereNotNull('custom_fields')
            ->limit(10)
            ->pluck('custom_fields');

        // $discoveredKeys = $sampleData->flatMap(function($json){
        //     return array_keys(json_decode($json, true) ?? []);
        // })->unique()->values()->all();
        $discoveredKeys = $sampleData->flatMap(function($json) {
            $decoded = is_array($json) ? $json : json_decode($json, true);
            return array_keys((array) $decoded); 
        })->unique()->values()->all();

        $this->table(
            ['Metric', 'Result', 'Status'],
            [
                ['Total API Traffic', $totalHits, 'Total hits received'],
                ['Valid Records', $successCount, '<info>Saved to DB</info>'],
                ['Duplicates Blocked', $duplicateCount, $duplicateCount > 0 ? '<warn>Logged</warn>' : 'Clean'],
                ['Ingestion Health', $successRate . '%', $successRate > 90 ? 'Excellent' : 'Review Required'],
            ]
        );

        if (!empty($discoveredKeys)) {
            $this->info("Optional Fields:");
            $this->bulletList($discoveredKeys);
        }

        if ($duplicateCount > 0) {
            $this->newLine();
            $this->error("Duplicates (Last 5):");
            
            $duplicates = DB::table('duplicate_reports')
                ->where('campaign_id', $id)
                ->latest()
                ->limit(5)
                ->get(['attempted_user_id', 'ip_address', 'created_at']);

            $this->table(
                ['User ID Attempted', 'Origin IP', 'Conflict Time'],
                $duplicates->map(fn($d) => (array)$d)->toArray()
            );
        }
    }

    private function bulletList(array $items){
        foreach ($items as $item) {
            $this->line(">{$item}");
        }
    }

}
