<?php

namespace App\Services;

use App\Models\Drug;
use Illuminate\Support\Facades\Http;

class DrugSearchService
{
    public function searchDrugs($ndcCodes)
    {
        $ndcCodes = array_filter(array_map('trim', explode(',', $ndcCodes)));
        $results = [];

        foreach ($ndcCodes as $ndc) {
            $drug = Drug::where('ndc_code', $ndc)->first();
            if ($drug) {
                $results[] = [
                    'ndc_code' => $drug->ndc_code,
                    'brand_name' => $drug->brand_name ?? '-',
                    'generic_name' => $drug->generic_name ?? '-',
                    'labeler_name' => $drug->labeler_name ?? '-',
                    'product_type' => $drug->product_type ?? '-',
                    'source' => 'Database'
                ];
            } else {
                $results[] = ['ndc_code' => $ndc, 'source' => 'Pending'];
            }
        }
        $missingNDCs = array_filter($results, fn($result) => $result['source'] === 'Pending');
        $missingNDCList = array_column($missingNDCs, 'ndc_code');

        if (!empty($missingNDCList)) {
            $apiQuery = implode(' OR ', array_map(fn($ndc) => "product_ndc:\"$ndc\"", $missingNDCList));
            $response = Http::get('https://api.fda.gov/drug/ndc.json', [
                'search' => $apiQuery,
                'limit' => 100,
            ]);
            if ($response->successful()) {
                $apiResults = $response->json()['results'] ?? [];
                foreach ($apiResults as $apiResult) {
                    $ndc = $apiResult['product_ndc'];
                    $drugData = [
                        'ndc_code' => $ndc,
                        'brand_name' => $apiResult['brand_name'] ?? null,
                        'generic_name' => $apiResult['generic_name'] ?? null,
                        'labeler_name' => $apiResult['labeler_name'] ?? null,
                        'product_type' => $apiResult['product_type'] ?? null,
                    ];
                    Drug::updateOrCreate(['ndc_code' => $ndc], $drugData);

                    foreach ($results as &$result) {
                        if ($result['ndc_code'] === $ndc && $result['source'] === 'Pending') {
                            $result = array_merge($drugData, ['source' => 'OpenFDA']);
                            $result['brand_name'] = $result['brand_name'] ?? '-';
                            $result['generic_name'] = $result['generic_name'] ?? '-';
                            $result['labeler_name'] = $result['labeler_name'] ?? '-';
                            $result['product_type'] = $result['product_type'] ?? '-';
                        }
                    }
                    unset($result);
                }
            }

            foreach ($results as &$result) {
                if ($result['source'] === 'Pending') {
                    $result = [
                        'ndc_code' => $result['ndc_code'],
                        'brand_name' => '-',
                        'generic_name' => '-',
                        'labeler_name' => '-',
                        'product_type' => '-',
                        'source' => 'Not Found'
                    ];
                }
            }
        }
        return $results;
    }

    public function exportToCsv($results)
    {
        $filename = 'drug_search_results_' . now()->format('Ymd_His') . '.csv';
        $handle = fopen(storage_path('app/public/' . $filename), 'w');
        fputcsv($handle, ['NDC Code', 'Brand Name', 'Generic Name', 'Labeler Name', 'Product Type', 'Source']);
        foreach ($results as $row) {
            fputcsv($handle, [
                $row['ndc_code'],
                $row['brand_name'],
                $row['generic_name'],
                $row['labeler_name'],
                $row['product_type'],
                $row['source'],
            ]);
        }
        fclose($handle);
        return $filename;
    }
}
