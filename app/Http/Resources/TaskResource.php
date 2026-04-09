<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'priority' => $this->priority,
            'priority_label' => $this->priority_label,
            'due_date' => $this->due_date,
            'completed_at' => $this->completed_at,
            'is_overdue' => $this->is_overdue,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
