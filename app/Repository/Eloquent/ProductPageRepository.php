<?php

use App\Models\ProductPage;
use App\Repository\ProductPageInterface;

class ProductPageRepository implements ProductPageInterface
{
    public function getAllProductPages()
    {
        return ProductPage::all();
    }

    public function getPageById(string $id)
    {
        return ProductPage::findOrFail($id);
    }

    public function getPageByUrl(string $url)
    {
        return ProductPage::findOrFail(['url' => $url]);
    }

    public function addProductPage(array $details)
    {
        return ProductPage::create($details);
    }
}