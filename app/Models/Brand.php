<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name', 'email'];

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
}
