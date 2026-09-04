<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Exception;

trait DocumentUploadTrait
{
    /**
     * Common logic to save document directly to the local disk, bypassing HTTP API.
     */
    private function saveDocumentDirectly($fileOrContent, $originalFileName, $category, $data, $isFileContent = false)
    {
        $category = strtoupper(trim($category));
        $allowedCategories = ['LOTTERY', 'APPLICATION', 'FINAL', 'FINAL-EMI', 'FINAL-EMI-PAY-SS'];

        if (!in_array($category, $allowedCategories)) {
            throw new Exception('Invalid or missing category. Allowed: ' . implode(', ', $allowedCategories));
        }

        $fileExt = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        $pathSegments = [];
        $newFileName = '';

        // Helper to safely get variables
        $getSafeParam = function ($key) use ($data) {
            $val = $data[$key] ?? '';
            return str_replace(['/', '\\', '..'], '-', trim($val));
        };

        if ($category === 'LOTTERY') {
            $schemeCode = $getSafeParam('scheme_code');
            $yyyy = $getSafeParam('yyyy') ?: date('Y');
            $mm = $getSafeParam('mm') ?: date('m');
            $dd = $getSafeParam('dd') ?: date('d');
            $propertyNumber = $getSafeParam('property_number');
            $pathSegments = [
                'jshb',
                'lottery',
                $schemeCode,
                date('Y'),
                date('m'),
                'payments',
                $propertyNumber,
                $yyyy,
                $mm,
                $dd
            ];
            $yymmdd = date('ymd');
            $rand5 = rand(10000, 99999);
            $newFileName = "lottery-payment-receipt-{$yymmdd}-{$rand5}.{$fileExt}";
        } else if ($category === 'APPLICATION') {
            $username = $getSafeParam('username');
            $propertyNumber = $getSafeParam('property_number');
            $documentName = $getSafeParam('document_name');
            $pathSegments = [
                'jshb',
                'application',
                'documents',
                $getSafeParam('application_for'),
                $getSafeParam('division_code'),
                $getSafeParam('subdivision_code'),
                $getSafeParam('property_category'),
                $getSafeParam('yyyy'),
                $getSafeParam('mm'),
                $getSafeParam('property_type'),
                $getSafeParam('property_income'),
                $propertyNumber,
                $username
            ];
            $rand4 = rand(1000, 9999);
            $safeDocName = preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower($documentName));
            if (empty($safeDocName)) $safeDocName = 'doc';

            $newFileName = "{$username}-{$propertyNumber}_{$safeDocName}_{$rand4}.{$fileExt}";
        } else if (in_array($category, ['FINAL', 'FINAL-EMI', 'FINAL-EMI-PAY-SS'])) {
            $username = $getSafeParam('username');
            $propertyNumber = $getSafeParam('property_number');
            $pathSegments = [
                'allottee',
                'documents',
                $getSafeParam('division_code'),
                $getSafeParam('subdivision_code'),
                $getSafeParam('property_category'),
                $getSafeParam('yyyy'),
                $getSafeParam('mm'),
                $getSafeParam('property_type'),
                $getSafeParam('property_income'),
                $propertyNumber,
                $username
            ];
            if ($category === 'FINAL-EMI') {
                $pathSegments[] = 'emi-recipet';
            }
            if ($category === 'FINAL-EMI-PAY-SS') {
                $pathSegments[] = 'emi-recipet/img';
            }

            // Clean filename exactly like the old API logic
            $nameWithoutExt = pathinfo($originalFileName, PATHINFO_FILENAME);
            $nameWithoutExt = preg_replace('/\.(jpg|jpeg|png|pdf|doc|docx)$/i', '', $nameWithoutExt);
            $nameWithoutExt = str_replace(' ', '_', $nameWithoutExt);
            $newFileName = $nameWithoutExt . ($fileExt ? '.' . $fileExt : '');
        }

        // Remove empty folder segments
        $pathSegments = array_filter($pathSegments, function ($val) {
            return $val !== '';
        });

        $subFolderPath = implode('/', $pathSegments);

        // HTTP URLs cannot be used for saving files. We MUST use a physical hard drive path.
        $baseTargetDir = dirname(base_path()) . '/jshb-doc';
        $targetDir = rtrim($baseTargetDir, '/') . '/documents/' . $subFolderPath;

        // Create directory if it does not exist
        if (!File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0777, true, true);
        }

        // Save the file
        if ($isFileContent) {
            File::put($targetDir . '/' . $newFileName, $fileOrContent);
        } else {
            $fileOrContent->move($targetDir, $newFileName);
        }

        // Return without leading slash to match the old logic (ltrim($path, '/'))
        $relativePath = 'documents/' . $subFolderPath . '/' . $newFileName;

        return [
            'file_path' => $relativePath,
            'file_name' => $newFileName
        ];
    }

    /**
     * Upload a document via direct file save (bypassing central Document API HTTP call).
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

        Log::info('Saving Document Directly (UploadedFile)', [
            'payload' => $apiPayload,
            'file'    => $file->getClientOriginalName()
        ]);

        return $this->saveDocumentDirectly(
            $file,
            $file->getClientOriginalName(),
            $category,
            $apiPayload,
            false
        );
    }

    /**
     * Upload raw file content via direct file save (bypassing central Document API HTTP call).
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

        Log::info('Saving Document Directly (Raw Content)', [
            'payload' => $apiPayload,
            'file'    => $fileName
        ]);

        return $this->saveDocumentDirectly(
            $fileContent,
            $fileName,
            $category,
            $apiPayload,
            true
        );
    }
}
