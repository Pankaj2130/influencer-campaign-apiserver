<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Influencer;
use Illuminate\Http\Request;

class InfluencerController extends Controller
{
    public function index(Request $request)
    {
        $query = Influencer::query();

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('min_followers')) {
            $query->where('followers', '>=', (int) $request->min_followers);
        }

        $perPage = $request->get('per_page', 20);
        $influencers = $query->paginate($perPage);

        if ($influencers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No influencers found for the given filters.',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Influencers fetched successfully.',
            'data' => $influencers,
        ]);
    }
}
