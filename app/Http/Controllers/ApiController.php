<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use App\Http\Resources\CommandResource;
use App\Http\Resources\InventoryItemResource;
use App\Repositories\ActivityRepository;
use App\Repositories\ItemsRepository;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function getItem(int $itemId, ItemsRepository $items)
    {
        return InventoryItemResource::make(
            $items->getItemByHash($itemId)
        );
    }

    public function getActivity(int $activityId, ActivityRepository $activities)
    {
        return ActivityResource::make(
            $activities->getActivityByHash($activityId)
        );
    }

    public function getCommands()
    {
        return CommandResource::collection(collect(\Artisan::all())->filter(function ($command) {
            return Str::contains($command->getName(), 'destiny');
        })->values());
    }

    public function runCommand(string $command)
    {
        $status = \Artisan::call($command);

        return response()->json([
            'data' => [
                'status' => $status,
                'output' => \Artisan::output(),
            ],
        ]);
    }
}
