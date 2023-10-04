<?php

namespace App\Http\Controllers;

use App\Models\FurnitureItem;
use App\Repository\Eloquent\FurnitureItemRepository;
use Illuminate\Http\Request;

class FurnitureItemController extends Controller
{
    public function index(Request $request)
    {
        $items = FurnitureItem::all();

        $height = $request->input('height');
        $width = $request->input('width');
        $depth = $request->input('depth');
        $type = $request->input('type');

        if ($height && $width && $depth && $type) {
            $items = $items
                ->where('height', '<', $height)
                ->where('width', '<', $width)
                ->where('depth', '<', $depth);
        }

        if($type && $type != 'all') {
            $items = $items->where('furniture_type', $type);
        }

        return [
            "furniture" => $items
        ];
    }


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
