<?php

namespace App\Containers\AppSection\Product\Tasks\DataExchange;

use App\Containers\AppSection\Product\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ProductsExport implements FromCollection, WithHeadings
{
    protected $products;
    public function __construct($products) {
        $this->products = $products;
    }

    public function collection(): Collection | array
    {
        return Product::query()->leftJoin(
            'categories',
            'products.category_id',
            '=',
            'categories.id')->select(
            'products.id',
            'products.name',
            'products.images',
            'products.description',
            'products.qty',
            'categories.name as category_name',
            'products.created_at',
            'products.updated_at',
            'products.price',
            'products.sale_price'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Images',
            'Description',
            'Quantity',
            'Category Name',
            'Created At',
            'Updated At',
            'Price',
            'Sale Price',
        ];
    }

    public function storeFile(string $fileName, string $disk = 'public'): string
    {
        $filePath = 'excels/'.$fileName;
        Excel::store($this, $filePath, $disk);

        return Storage::disk($disk)->url($filePath);
    }
}
