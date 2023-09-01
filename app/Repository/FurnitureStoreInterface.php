<?php
namespace App\Repository;

use App\Models\FurnitureStore;
use Illuminate\Database\Eloquent\Collection;

interface FurnitureStoreInterface
{
    public function getAllStores();

    public function getStoreById(string $id);

    public function getStoreByUrl(string $url);

    public function setNumProductPages(string $id, int $numProductPages);
}