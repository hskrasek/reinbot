<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryItemResource;
use App\Repositories\ItemsRepository;

class ApiController extends Controller
{
    public function getItem(int $itemId, ItemsRepository $items)
    {
        return InventoryItemResource::make(
            $items->getItemByHash($itemId)
        );
    }
}
