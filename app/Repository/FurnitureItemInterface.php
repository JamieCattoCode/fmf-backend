<?php
namespace App\Repository;

interface FurnitureItemInterface
{
    public function getAllFurnitureItems();

    public function getPageById(string $id);

    public function getPageByUrl(string $url);

    public function getItemsByFurnitureStore(string $furnitureStoreId);

    public function pageExists(string $url);

    public function addFurnitureItem(array $details);

    public function firstOrCreateFurnitureItem(array $details);
}