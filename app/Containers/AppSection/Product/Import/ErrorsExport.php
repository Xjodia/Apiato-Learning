<?php

namespace App\Containers\AppSection\Product\Import;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ErrorsExport implements FromCollection, WithHeadings
{
    protected $errors;

    public function __construct($errors)
    {
        $this->errors = $errors;
    }

    public function collection()
    {
        return new Collection($this->errors);
    }

    public function headings(): array
    {
        // Adjust the headings based on your error data structure
        return [
            'name',
            'images',
            'description',
            'qty',
            'category_id',
            'price',
            'sale_price',
            'error',
        ];
    }
}
