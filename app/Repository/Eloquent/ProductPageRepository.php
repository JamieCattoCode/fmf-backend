<?php
namespace App\Repository\Eloquent;

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
        return ProductPage::where(['url' => $url])->firstOrFail();
    }

    public function pageExists(string $url)
    {
        return ProductPage::where('url', $url)->exists();
    }

    public function addProductPage(array $details)
    {
        return ProductPage::create($details);
    }
}