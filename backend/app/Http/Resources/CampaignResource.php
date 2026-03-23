<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'client_id'=> $this->client_id,
            'name' => $this->name,
            'start_date'=> $this->start_date,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}