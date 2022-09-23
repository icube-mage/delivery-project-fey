<?php

namespace App\Exports;

use App\Models\CatalogPrice;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class FileDataExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return CatalogPrice::select('sku', 'marketplace', 'discount_price')->get();
    }

    public function headings():array{
        return[
            'SKU',
            'Marketplace',
            'Discount'
        ];
    } 
}
