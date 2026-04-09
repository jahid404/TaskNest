<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';

    protected $fillable = [
        'name',
        'description',
        'status',
        'priority',
        'due_date',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    public static function priorityOptions(): array
    {
        return [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::priorityOptions()[$this->priority] ?? ucfirst($this->priority);
    }

    public function getIsOverdueAttribute(): bool
    {
        return (bool) ($this->due_date && $this->status !== self::STATUS_COMPLETED && $this->due_date->lt(today()));
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! filled($search)) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($search) {
            $builder->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        return filled($status) ? $query->where('status', $status) : $query;
    }

    public function scopeFilterPriority(Builder $query, ?string $priority): Builder
    {
        return filled($priority) ? $query->where('priority', $priority) : $query;
    }

    public function scopeFilterDue(Builder $query, ?string $due): Builder
    {
        return match ($due) {
            'overdue' => $query->whereDate('due_date', '<', now()->toDateString())
                ->where('status', '!=', self::STATUS_COMPLETED),
            'today' => $query->whereDate('due_date', now()->toDateString()),
            'upcoming' => $query->whereDate('due_date', '>', now()->toDateString()),
            'undated' => $query->whereNull('due_date'),
            default => $query,
        };
    }

    public function scopeApplySort(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'due_soon' => $query->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
                ->orderBy('due_date')
                ->latest(),
            'priority' => $query->orderByRaw("
                CASE priority
                    WHEN 'high' THEN 1
                    WHEN 'medium' THEN 2
                    ELSE 3
                END
            ")->latest(),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };
    }
}
