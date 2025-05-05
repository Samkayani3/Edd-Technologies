<?php

namespace App\Services\ReceiptFormatter;

use App\Models\Equipments;

interface ReceiptFormatter
{
    public function format(Equipments $equipment): string;
}
