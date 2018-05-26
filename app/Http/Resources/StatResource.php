<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatResource extends JsonResource
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
            'name'        => data_get($this->json, 'displayProperties.name'),
            'description' => data_get($this->json, 'displayProperties.description'),
            'icon'        => data_get($this->json, 'displayProperties.icon'),
        ];
    }
}
