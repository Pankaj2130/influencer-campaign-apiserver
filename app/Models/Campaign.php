<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = ['name', 'budget', 'start_date', 'end_date', 'status', 'brand_id'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function influencers()
    {
        return $this->belongsToMany(Influencer::class, 'campaign_influencer');
    }
}
