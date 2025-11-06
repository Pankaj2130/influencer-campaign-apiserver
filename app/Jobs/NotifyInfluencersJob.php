<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyInfluencersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campaign;
    public $influencerIds;

    /**
     * Create a new job instance.
     */
    public function __construct($campaign, array $influencerIds = [])
    {
        $this->campaign = $campaign;
        $this->influencerIds = $influencerIds;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $ids = implode(',', $this->influencerIds ?: []);
        Log::info("Notifying influencers for campaign: {$this->campaign->name}. Influencer IDs: {$ids}");

        if (!empty($this->influencerIds)) {
            $influencers = \App\Models\Influencer::whereIn('id', $this->influencerIds)->get();
            foreach ($influencers as $inf) {
                Log::info("Simulated email to influencer {$inf->id} ({$inf->name}) for campaign {$this->campaign->id}");
            }
        }
    }
}
