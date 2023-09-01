<?php
namespace App\Repository\Eloquent;

use App\Repository\FurnitureStoreInterface;
use App\Models\FurnitureStore;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class FurnitureStoreRepository implements FurnitureStoreInterface
{
    public function getAllStores()
    {
        return FurnitureStore::all();
    }

    public function getStoreById(string $id)
    {
        return FurnitureStore::findOrFail($id);
    }

    public function getStoreByUrl(string $url)
    {
        return FurnitureStore::findOrFail(['url' => $url]);
    }

    public function setNumProductPages(string $id, int $numProductPages)
    {
        return FurnitureStore::whereId($id)->update(['num_product_pages' => $numProductPages]);
    }
}