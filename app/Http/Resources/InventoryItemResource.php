<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => data_get($this->json, 'hash'),
            'name'        => data_get($this->json, 'displayProperties.name'),
            'description' => data_get($this->json, 'displayProperties.description'),
            'icon'        => data_get($this->json, 'displayProperties.icon'),
            'screenshot'  => data_get($this->json, 'screenshot'),
            'class'       => $this->when(null !== $this->class, function () {
                return data_get($this->class->json, 'displayProperties.name');
            }),
            'lore'        => LoreResource::make($this->lore),
            'stats'       => $this->when(null !== $this->stats, function () {
                return StatResource::collection($this->stats);
            }),
            'json'        => $this->when($request->has('debug'), $this->json),
        ];
    }
}
