<?php

namespace App\Containers\AppSection\Product\Jobs;

use App\Containers\AppSection\Product\Mails\ProductExporterMail;
use App\Containers\AppSection\Product\Tasks\DataExchange\ProductsExport;
use App\Ship\Parents\Jobs\Job as ParentJob;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class  ExportJob extends ParentJob implements ShouldQueue
 {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $products;
    private string $recipientEmail;

    public function __construct($products, string $recipientEmail)
    {
        $this->products = $products;
        $this->recipientEmail = $recipientEmail;
    }

    public function handle(): JsonResponse
    {
        $export = new ProductsExport($this->products);
        $fileName = 'products.xlsx';
        $filePath = 'storage/excels/' . $fileName;
        $fileLocation = url($filePath);
        // Construct the full file path
        $fullPath = public_path($filePath);

        $counter = 1;
        while (file_exists($fullPath)) {
            // Increment the counter and update the file name
            $counter++;
            $fileName = 'products_' . $counter . '.xlsx';
            $filePath = 'storage/excels/' . $fileName;
            $fullPath = public_path($filePath);
            $fileLocation = url($filePath);
        }

        $export->storeFile($fileName, 'public');

        try {
            Log::info('Export job handled successfully.');
            Mail::to($this->recipientEmail)->send(new ProductExporterMail($fileLocation, $this->recipientEmail));
            Log::info('Email sent successfully.');
            return response()->json([
                'message' => 'Export job has been dispatched.',
            ], 200);
        } catch (Exception $exception) {
            Log::error('Error sending email: ' . $exception->getMessage());

            return response()->json([
                'message' => 'Error sending email. Please try again later.',
            ], 500);
        }
    }
 }
