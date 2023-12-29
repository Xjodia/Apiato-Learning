<?php

namespace App\Containers\AppSection\Product\Import;

use App\Containers\AppSection\Product\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, WithMapping
{
    private $filePath;
    private $errors = [];

    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    public function model(array $row)
    {
        $images = $this->uploadImage($row['images']);

        $validator = Validator::make($row, [
            'name' => 'required|string|max:255',
            'images' => 'required|string',
            'description' => 'nullable|string',
            'qty' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id|min:0',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            // Thêm thông tin lỗi vào mảng errors
            $this->errors[] = array_merge($row, ['error' => implode(', ', $validator->errors()->all())]);
        } else {
            // Nếu không có lỗi, trả về toàn bộ thông tin dòng dữ liệu
            $this->errors[] = $row;
        }

        $product = new Product([
            'name' => $row['name'],
            'images' => $images,
            'description' => $row['description'],
            'qty' => $row['qty'],
            'category_id' => $row['category_id'],
            'price' => $row['price'],
            'sale_price' => $row['sale_price'],
        ]);

        return $product;
    }

    public function rules(): array
    {
        return [
        ];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function map($row): array
    {
        // Trả về chỉ các cột cần thiết
        return [
            'name' => $row['name'],
            'images' => $row['images'],
            'description' => $row['description'],
            'qty' => $row['qty'],
            'category_id' => $row['category_id'],
            'price' => $row['price'],
            'sale_price' => $row['sale_price'],
            'error' => isset($this->errors[$row['name']]) ? $this->errors[$row['name']]['error'] : '',
        ];
    }

    private function uploadImage($imageName)
    {
        $imageFileName = basename($imageName);

        return Storage::url('images/' . $imageFileName);
    }
}
