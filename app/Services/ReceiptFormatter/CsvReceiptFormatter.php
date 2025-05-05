<?php

namespace App\Services\ReceiptFormatter;

use App\Models\Equipments;

class CsvReceiptFormatter implements ReceiptFormatter
{
    public function format(Equipments $equipment): string
    {
        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, ['Field', 'Value']);
        fputcsv($csv, ['Customer', $equipment->customer->name ?? 'N/A']);
        fputcsv($csv, ['Technician', $equipment->technician->name ?? 'N/A']);
        fputcsv($csv, ['Equipment ID', $equipment->id]);
        fputcsv($csv, ['Price', $equipment->price ?? 'Not Set']);

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        return $content;
    }
}
