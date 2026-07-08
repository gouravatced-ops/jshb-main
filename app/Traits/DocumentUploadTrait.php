<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

trait DocumentUploadTrait
{
    /**
     * Upload a document via the central Document API.
     *
     * @param UploadedFile $file The uploaded file.
     * @param string $category The category (e.g. 'LOTTERY').
     * @param string $schemeCode The scheme code.
     * @param string $propertyNumber The property number.
     * @param string|int $yyyy Year
     * @param string|int $mm Month
     * @param string|int $dd Day
     * @param string|null $oldFilePath Optional old file path to delete before uploading.
     * @return array [ 'file_path' => ..., 'file_name' => ... ]
     * @throws \Exception
     */
    public function uploadToDocumentApi(
        UploadedFile $file,
        $category,
        $schemeCode,
        $propertyNumber,
        $yyyy,
        $mm,
        $dd,
        $oldFilePath = null
    ) {
        // Delete old file if provided
        if (!empty($oldFilePath)) {
            $oldFile = public_path($oldFilePath);
            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }
        }

        $apiPayload = [
            'project'         => 'jshb',
            'category'        => $category,
            'scheme_code'     => $schemeCode ?? 'SCH',
            'property_number' => $propertyNumber ?? 'PROP',
            'yyyy'            => $yyyy,
            'mm'              => $mm,
            'dd'              => $dd,
        ];

        Log::info('Document API Request', [
            'url'     => env('DOC_API_URL'),
            'payload' => $apiPayload,
            'file'    => $file->getClientOriginalName()
        ]);

        $response = Http::withToken(env('DOC_API_TOKEN'))
            ->withHeaders(['X-API-KEY' => env('DOC_API_TOKEN')])
            ->attach('file', file_get_contents($file), $file->getClientOriginalName())
            ->post(env('DOC_API_URL'), $apiPayload);

        Log::info('Document API Response', [
            'status'   => $response->status(),
            'response' => $response->json()
        ]);

        if ($response->successful() && $response->json('status') === 'success') {
            $responseData = $response->json('data');
            $receiptPath = ltrim($responseData['file_path'], '/');
            $receiptFile = basename($receiptPath);

            return [
                'file_path' => $receiptPath,
                'file_name' => $receiptFile
            ];
        } else {
            throw new \Exception('Failed to upload document to API: ' . $response->body());
        }
    }
}
