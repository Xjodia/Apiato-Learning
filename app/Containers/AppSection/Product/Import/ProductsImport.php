<?php
namespace App\Containers\AppSection\Product\Import;

use App\Containers\AppSection\Product\Models\Product;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $images = $this->uploadImage($row['images']);

        return new Product([
            'name' => $row['name'],
            'images' => $images,
            'description' => $row['description'],
            'qty' => $row['qty'],
            'category_id' => $row['category_id'],
            'price' => $row['price'],
            'sale_price' => $row['sale_price'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'images' => 'required|string',
            'description' => 'nullable|string',
            'qty' => 'required|integer|min:0',
            'category_id' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
        ];
    }


    private function uploadImage($imageName)
    {
        $imageFileName = basename($imageName);

        return Storage::url('images/' . $imageFileName);
    }
}
