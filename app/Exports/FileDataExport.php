<?php

namespace App\Exports;

use App\Models\CatalogPriceTemp;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class FileDataExport implements FromCollection, WithHeadings
{
    public function __construct(string $brand, string $marketplace)
    {
        $this->brand = $brand;
        $this->marketplace = $marketplace;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $userId = Auth::id();
        return CatalogPriceTemp::select('sku', 'marketplace', 'discount_price')->where('user_id',$userId)->orWhere('brand', $this->brand)->orWhere('marketplace', $this->marketplace)->get();
    }

    public function headings():array{
        return[
            'SKU',
            'Marketplace',
            'Discount'
        ];
    } 
}
