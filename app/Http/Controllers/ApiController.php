<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use App\Http\Resources\InventoryItemResource;
use App\Repositories\ActivityRepository;
use App\Repositories\ItemsRepository;

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
}
