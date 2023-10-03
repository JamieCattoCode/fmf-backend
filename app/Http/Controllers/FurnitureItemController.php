<?php

namespace App\Http\Controllers;

use App\Models\FurnitureItem;
use App\Repository\Eloquent\FurnitureItemRepository;
use Illuminate\Http\Request;

class FurnitureItemController extends Controller
{
    public function getFurnitureTypes()
    {
        return [
            "furniture_types" => config('constants.furnitureTypes')
        ];
    }

    public function getFurnitureWithType(string $type, FurnitureItemRepository $furnitureItemRepository)
    {
        $furniture = $furnitureItemRepository->getItemsByFurnitureType($type);

        return [
            "furniture" => $furniture
        ];
    }
}
