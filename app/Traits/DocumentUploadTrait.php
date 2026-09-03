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
        $oldFilePath = null,
        $extraData = []
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

        if (!empty($extraData)) {
            $apiPayload = array_merge($apiPayload, $extraData);
        }

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = pathinfo($originalName, PATHINFO_FILENAME);
        // Remove common image/document extensions from the end of the filename to prevent double extensions
        $filename = preg_replace('/\.(jpg|jpeg|png|pdf|doc|docx)$/i', '', $filename);
        // Replace spaces with underscores
        $filename = str_replace(' ', '_', $filename);
        $cleanFileName = $filename . '.' . $extension;

        Log::info('Document API Request', [
            'url'     => config('jshb.doc_upload_api_url'),
            'payload' => $apiPayload,
            'file'    => $cleanFileName
        ]);

        $response = Http::withToken(config('jshb.doc_api_token'))
            ->withHeaders(['X-API-KEY' => config('jshb.doc_api_token')])
            ->attach('file', file_get_contents($file), $cleanFileName)
            ->post(config('jshb.doc_upload_api_url'), $apiPayload);

        Log::info('Document API Response', [
            'status'   => $response->status(),
            'response' => $response->json()
        ]);

        if ($response->successful() && $response->json('status') === 'success') {
            $responseData = $response->json('data');
            $filePathData = $responseData['file_path'] ?? '';
            $filePathStr = is_array($filePathData) ? implode('/', $filePathData) : $filePathData;
            $receiptPath = ltrim($filePathStr, '/');
            $receiptFile = basename($receiptPath);

            return [
                'file_path' => $receiptPath,
                'file_name' => $receiptFile
            ];
        } else {
            throw new \Exception('Failed to upload document to API: ' . $response->body());
        }
    }

    /**
     * Upload raw file content via the central Document API.
     *
     * @param string $fileContent The raw file content (e.g. generated PDF stream).
     * @param string $fileName The name of the file to save as.
     * @param string $category The category (e.g. 'ALLOTMENT').
     * @param string $schemeCode The scheme code.
     * @param string $propertyNumber The property number.
     * @param string|int $yyyy Year
     * @param string|int $mm Month
     * @param string|int $dd Day
     * @return array [ 'file_path' => ..., 'file_name' => ... ]
     * @throws \Exception
     */
    public function uploadContentToDocumentApi(
        $fileContent,
        $fileName,
        $category,
        $schemeCode,
        $propertyNumber,
        $yyyy,
        $mm,
        $dd,
        $extraData = []
    ) {
        $apiPayload = [
            'project'         => 'jshb',
            'category'        => $category,
            'scheme_code'     => $schemeCode ?? 'SCH',
            'property_number' => $propertyNumber ?? 'PROP',
            'yyyy'            => $yyyy,
            'mm'              => $mm,
            'dd'              => $dd,
        ];

        if (!empty($extraData)) {
            $apiPayload = array_merge($apiPayload, $extraData);
        }

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $nameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
        $nameWithoutExt = preg_replace('/\.(jpg|jpeg|png|pdf|doc|docx)$/i', '', $nameWithoutExt);
        $nameWithoutExt = str_replace(' ', '_', $nameWithoutExt);
        $cleanFileName = $nameWithoutExt . ($extension ? '.' . $extension : '');

        Log::info('Document API Request (Content)', [
            'url'     => config('jshb.doc_upload_api_url'),
            'payload' => $apiPayload,
            'file'    => $cleanFileName
        ]);

        $response = Http::withToken(config('jshb.doc_api_token'))
            ->withHeaders(['X-API-KEY' => config('jshb.doc_api_token')])
            ->attach('file', $fileContent, $cleanFileName)
            ->post(config('jshb.doc_upload_api_url'), $apiPayload);

        Log::info('Document API Response', [
            'status'   => $response->status(),
            'response' => $response->json()
        ]);

        if ($response->successful() && $response->json('status') === 'success') {
            $responseData = $response->json('data');
            $filePathData = $responseData['file_path'] ?? '';
            $filePathStr = is_array($filePathData) ? implode('/', $filePathData) : $filePathData;
            $receiptPath = ltrim($filePathStr, '/');
            $receiptFile = basename($receiptPath);

            return [
                'file_path' => $receiptPath,
                'file_name' => $receiptFile
            ];
        } else {
            throw new \Exception('Failed to upload document content to API: ' . $response->body());
        }
    }
}
