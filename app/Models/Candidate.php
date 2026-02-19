<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'position_id',
        'name',
        'description',  // was 'bio'
        'photo',
        'manifesto',
        'is_approved',  // ✅ was missing - this is why approve wasn't saving
    ];

    protected $casts = [
        'is_approved' => 'boolean', // ✅ ensures toggle works correctly
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function voteCount(): int
    {
        return $this->votes()->count();
    }
}