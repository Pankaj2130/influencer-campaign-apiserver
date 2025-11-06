<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Campaign;

class Influencer extends Model
{
    protected $fillable = ['name', 'category', 'followers', 'platform'];

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_influencer');
    }   
}