<?php
namespace App\Repository;

interface FurnitureItemInterface
{
    public function getAllFurnitureItems();

    public function getItemById(string $id);

    public function getItemByUrl(string $url);

    public function getItemsByFurnitureStore(string $furnitureStoreId);

    public function getItemsByFurnitureType(string $type);

    public function itemExists(string $url);

    public function addFurnitureItem(array $details);

    public function firstOrCreateFurnitureItem(array $details);
}