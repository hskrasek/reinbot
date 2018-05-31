<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
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
            'id'          => data_get($this, 'json.hash'),
            'name'        => data_get($this, 'json.displayProperties.name'),
            'description' => data_get($this, 'json.displayProperties.description'),
            'icon'        => data_get($this, 'json.displayProperties.icon'),
            'screenshot'  => data_get($this, 'json.pgcrImage'),
            'rewards'     => InventoryItemResource::collection($this->rewards),
            'destination' => DestinationResource::make($this->destination),
            'place'       => PlaceResource::make($this->place),
            'challenges'  => $this->when(null !== $this->challenges && $this->challenges->isNotEmpty(), function () {
                return ChallengeResource::collection($this->challenges);
            }),
            'mode'        => ActivityModeResource::make($this->mode),
            'json'        => $this->when($request->has('debug'), $this->json),
        ];
    }
}
