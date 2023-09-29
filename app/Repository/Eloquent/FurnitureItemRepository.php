<?php
namespace App\Repository\Eloquent;

use App\Models\FurnitureItem;
use App\Repository\FurnitureItemInterface;

class FurnitureItemRepository implements FurnitureItemInterface
{
    public function getAllFurnitureItems()
    {
        return FurnitureItem::all();
    }

    public function getPageById(string $id)
    {
        return FurnitureItem::findOrFail($id);
    }

    public function getPageByUrl(string $url)
    {
        return FurnitureItem::where(['url' => $url])->firstOrFail();
    }

    public function getItemsByFurnitureStore(string $furnitureStoreId)
    {
        return FurnitureItem::where(['furniture_store_id' => $furnitureStoreId])->get();
    }

    public function pageExists(string $url)
    {
        return FurnitureItem::where('url', $url)->exists();
    }

    public function addFurnitureItem(array $details): FurnitureItem
    {
        return FurnitureItem::create($details);
    }

    public function firstOrCreateFurnitureItem(array $details)
    {
        return FurnitureItem::firstOrCreate($details);
    }
}