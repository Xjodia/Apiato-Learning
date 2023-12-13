<?php

namespace App\Containers\AppSection\Product\Mails;

use App\Ship\Parents\Exceptions\Exception;
use App\Ship\Parents\Mails\Mail as ParentMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ProductExporterMail extends ParentMail implements ShouldQueue
{
    use Queueable;
    protected string $file;
    protected string $emailName;
    public function __construct($file, $emailName) {
        $this->file = $file;
        $this->emailName = $emailName;
    }

    /**
     * @throws Exception
     */
    public function build(): static
    {
        try {
            Log::info('Mail build successful.');
            return $this->view('appSection@product::product-export-email')
                ->attach($this->file, ['as' => 'products.xlsx'])
                ->with([
                    'file' => $this->file,
                    'emailName' => $this->emailName
                ]);
        } catch (Exception $exception) {
            Log::error('Error building mail: ' . $exception->getMessage());
            throw $exception; // Rethrow the exception to let it bubble up
        }
    }
}
