<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampaignRequest;
use App\Models\Campaign;
use App\Models\Influencer;
use Illuminate\Http\Request;
use App\Jobs\NotifyInfluencersJob;
use Illuminate\Support\Facades\Validator;


class CampaignController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'budget' => 'required|numeric|min:1',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
        ], [
            'name.required' => 'Campaign name is required.',
            'budget.required' => 'Budget is required.',
            'budget.numeric' => 'Budget must be a numeric value.',
            'startDate.required' => 'Start date is required.',
            'endDate.required' => 'End date is required.',
            'endDate.after' => 'The end date must be after the start date.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $startDate = date('Y-m-d', strtotime($request->startDate));
            $endDate = date('Y-m-d', strtotime($request->endDate));

            $campaign = Campaign::create([
                'name' => $request->name,
                'budget' => $request->budget,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'draft',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Campaign created successfully.',
                'data' => $campaign,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while creating the campaign.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function index()
    {
        try {
            $campaigns = Campaign::with('influencers')->get();

            if ($campaigns->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No campaigns found.',
                    'data' => [],
                ], 404);
            }

            $formatted = $campaigns->map(function ($c) {
                $totalFollowers = $c->influencers->sum('followers');
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'budget' => $c->budget,
                    'start_date' => date('Y-m-d', strtotime($c->start_date)),
                    'end_date' => date('Y-m-d', strtotime($c->end_date)),
                    'status' => $c->status,
                    'total_influencers' => $c->influencers->count(),
                    'total_followers' => $totalFollowers,
                    'influencers' => $c->influencers,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Campaigns fetched successfully.',
                'data' => $formatted,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching campaigns.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function assign(Request $request, $id)
    {
        try {
            $request->validate([
                'influencer_ids' => 'required|array|min:1',
                'influencer_ids.*' => 'integer|exists:influencers,id',
            ], [
                'influencer_ids.required' => 'Please provide at least one influencer ID.',
                'influencer_ids.*.exists' => 'One or more influencer IDs are invalid.',
            ]);

            $campaign = Campaign::findOrFail($id);

            $existing = $campaign->influencers()->pluck('influencers.id')->toArray();

            $newInfluencers = array_values(array_diff($request->influencer_ids, $existing));

            if (!empty($newInfluencers)) {
                $now = now();
                $pivotData = [];

                foreach ($newInfluencers as $influencerId) {
                    $pivotData[$influencerId] = [
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                $campaign->influencers()->attach($pivotData);

                NotifyInfluencersJob::dispatch($campaign, $newInfluencers);
            }

            return response()->json([
                'success' => true,
                'message' => 'Influencers assigned successfully.',
                'campaign_id' => $campaign->id,
                'new_influencers_attached' => $newInfluencers,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Campaign not found.',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while assigning influencers.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            $campaign = Campaign::with('influencers')->find($id);

            if (!$campaign) {
                return response()->json([
                    'success' => false,
                    'message' => 'Campaign not found.',
                ], 404);
            }

            $totalFollowers = $campaign->influencers->sum('followers');

            $data = [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'budget' => $campaign->budget,
                'startDate' => date('Y-m-d', strtotime($campaign->start_date)),
                'endDate' => date('Y-m-d', strtotime($campaign->end_date)),
                'status' => $campaign->status,
                'totalInfluencers' => $campaign->influencers->count(),
                'totalFollowers' => $totalFollowers,
                'assignedInfluencers' => $campaign->influencers->map(function ($inf) {
                    return [
                        'id' => $inf->id,
                        'name' => $inf->name,
                        'followers' => $inf->followers,
                        'platform' => $inf->platform,
                    ];
                }),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Campaign details fetched successfully.',
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching campaign details.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
