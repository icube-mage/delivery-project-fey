<?php

namespace App\Exports;

use App\Models\CatalogPriceTemp;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class FileDataExport implements FromCollection, WithHeadings
{
    public function __construct(string $marketplace, string $brand, array $datas)
    {
        $this->marketplace = $marketplace;
        $this->brand = $brand;
        $this->datas = $datas;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $filterColumn = [];
        foreach($this->datas as $data){
            $filterColumn[] = [
                "sku"=>$data->sku,
                "product_name"=>$data->product_name,
                "marketplace"=>$this->marketplace,
                "discount_price"=>$data->price,
                "warehouse"=>$data->warehouse,
            ];
        }
        return collect($filterColumn);
    }

    public function headings():array{
        return[
            'SKU',
            'Product Name',
            'Marketplace',
            'Discount',
            'Warehouse'
        ];
    } 
}
