<?php

namespace App\Http\Resources;

use Illuminate\Console\Command;
use Illuminate\Http\Resources\Json\JsonResource;

class CommandResource extends JsonResource
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
        /** @var Command $command */
        $command = $this->resource;
        return [
            'name'        => $command->getName(),
            'description' => $command->getDescription(),
        ];
    }
}
