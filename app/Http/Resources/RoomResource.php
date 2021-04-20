<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'length' => $this->length,
            'width' => $this->width,
            'price_per_month' => $this->price_per_month,
            'used_by' => $this->used_by,
            'used_until' => $this->used_until,
            'relations' => [
                'roomImage' => RoomImageResource::collection($this->whenLoaded('roomImage'))
            ]
        ];
    }
}
