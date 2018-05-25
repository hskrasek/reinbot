<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryItemResource;
use App\InventoryItem;

class ApiController extends Controller
{
    public function getItem(int $itemId)
    {
        return InventoryItemResource::make(
            InventoryItem::byBungieId($itemId)->first()
        );
    }
}
