<?php

namespace App\Exports;

use App\Models\CatalogPriceTemp;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class FileDataExport implements FromCollection, WithHeadings
{
    public function __construct(string $marketplace, string $brand)
    {
        $this->marketplace = $marketplace;
        $this->brand = $brand;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $userId = Auth::id();
        return CatalogPriceTemp::select('sku', 'marketplace', 'discount_price', 'warehouse')->where('user_id',$userId)->where('marketplace', $this->marketplace)->where('brand', $this->brand)->get();
    }

    public function headings():array{
        return[
            'SKU',
            'Marketplace',
            'Discount',
            'Warehouse'
        ];
    } 
}
