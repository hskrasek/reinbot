<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DestinationResource extends JsonResource
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
        ];
    }
}
