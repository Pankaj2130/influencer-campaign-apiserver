<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use Carbon\Carbon;

class MarkCampaignsCompleted extends Command
{
    protected $signature = 'campaigns:mark-completed';
    protected $description = 'Mark expired campaigns as completed';

    public function handle()
    {
        $today = date('Y-m-d');

        $count = Campaign::where('end_date', '<', $today)
            ->where('status', '!=', 'completed')
            ->update(['status' => 'completed']);

        $this->info("$count campaigns marked as completed.");
    }
}
