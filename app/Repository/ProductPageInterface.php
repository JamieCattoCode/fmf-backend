<?php
namespace App\Repository;

interface ProductPageInterface
{
    public function getAllProductPages();

    public function getPageById(string $id);

    public function getPageByUrl(string $url);

    public function addProductPage(array $details);
}