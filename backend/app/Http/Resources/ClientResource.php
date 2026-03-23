<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            "created_at" => $this->created_at->format('Y-m-d H:i:s'),
            "id" => $this->id
        ];
    }
}



// {
//     "message": "Client created successfully",
//     "client": {
//         "name": "MTN Yello",
//         "updated_at": "2026-03-22T21:42:45.000000Z",
//         "created_at": "2026-03-22T21:42:45.000000Z",
//         "id": 2
//     }
// }
