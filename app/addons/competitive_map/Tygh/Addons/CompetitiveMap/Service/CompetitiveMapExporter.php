<?php

namespace Tygh\Addons\CompetitiveMap\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tygh\Addons\VendorRating\ServiceProvider;

class CompetitiveMapExporter
{
    protected $products;
    protected $meta;

    public function __construct(array $products, array $meta)
    {
        $this->products = $products;
        $this->meta = $meta;
    }

    public function downloadXlsx(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Metadata
        $sheet->setCellValue('A1', $this->meta['filename'] ?? '');
        $sheet->setCellValue('A2', 'URL: ' . $this->meta['referer']);
        $sheet->setCellValue('A3', 'Дата/время: ' . date('d.m.Y H:i:s'));
        $sheet->setCellValue('A4', 'Компания: ' . $this->meta['company_name']);

        // 1) Collect all unique feature names
        $feature_names = [];
        foreach ($this->products as $product) {
            if (!empty($product['product_features'])) {
                foreach ($product['product_features'] as $feature) {
                    if (!isset($feature['variants'])) {
                        continue;
                    }
                    $feature_names[$feature['description']] = true;
                }
            }
        }
        $feature_names = array_keys($feature_names);

        // 2) Create headers
        $headers = ['Продавец', 'Деталь (название)', 'Цена', 'Наличие', 'Рейтинг'];
        $headers = array_merge($headers, $feature_names);

        $row = 6;
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }

        // 3) Fill table
        $row = 7;
        $rating_service = ServiceProvider::getVendorService();

        foreach ($this->products as $product) {
            $col = 'A';
            $sheet->setCellValue($col++ . $row, $product['company_name'] ?? '—');
            $sheet->setCellValue($col++ . $row, $product['product'] ?? '');
            $sheet->setCellValue($col++ . $row, $product['price'] ?? '');
            $sheet->setCellValue($col++ . $row, $product['stock_info'] ?? '—');
            $sheet->setCellValue($col++ . $row, $rating_service->getRelativeRating($product['company_id']) ?? '—');


            $feature_map = [];
            if (!empty($product['product_features'])) {
                foreach ($product['product_features'] as $feature) {
                    if (!isset($feature['variants'])) continue;
                    $feature_variant = array_column($feature['variants'], 'variant');
                    $feature_map[$feature['description']] = $feature_variant[0] ?? '';
                }
            }

            // Fill characteristics columns
            foreach ($feature_names as $fname) {
                $sheet->setCellValue($col++ . $row, $feature_map[$fname] ?? '');
            }

            $row++;
        }

        // Download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $this->meta['filename'] . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

    }

}
