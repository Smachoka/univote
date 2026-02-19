<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportLog extends Model
{
    protected $fillable = [
        'admin_id',
        'filename',
        'status',
        'total_rows',
        'success_count',
        'failure_count',
        'error_file',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'completed'  => 'badge-success',
            'failed'     => 'badge-danger',
            'processing' => 'badge-warning',
            default      => 'badge-secondary',
        };
    }
}