<?php

namespace App\Services\ReceiptFormatter;

use App\Models\Equipments;

class HtmlReceiptFormatter implements ReceiptFormatter
{
    public function format(Equipments $equipment): string
    {
        return view('admin.receipt', compact('equipment'))->render();
    }
}
