<?php 
namespace App\Exports;

use App\Models\CatalogPrice;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CatalogPriceDetailExport implements FromCollection,WithHeadings
{
    public function __construct(string $hash)
    {
        $this->hash = $hash;
    }
    public function headings():array{
        return[
            'Marketplace',
            'brand',
            'SKU',
            'Product Name',
            'Price' ,
            'Discount',
            'User',
            'Date'
        ];
    } 
    public function collection()
    {
        $catalogPricesAll = collect([]);
        CatalogPrice::select('marketplace', 'brand', 'sku', 'catalog_prices.product_name as product', 'retail_price','discount_price','users.name', 'catalog_prices.created_at as date')
        ->leftJoin('users', 'catalog_prices.user_id', '=', 'users.id')
        ->where('upload_hash', $this->hash)
        ->chunk(1000, function ($catalogPrices) use($catalogPricesAll){
            foreach ($catalogPrices as $catalogPrice) {
                $catalogPricesAll->push([
                    "marketplace"=>$catalogPrice->marketplace,
                    "brand"=>$catalogPrice->brand,
                    "sku"=>$catalogPrice->sku,
                    "product"=>$catalogPrice->product,
                    "retail_price"=>$catalogPrice->retail_price,
                    "discount_price"=>$catalogPrice->discount_price,
                    "name"=>$catalogPrice->name,
                    "date"=>$catalogPrice->date,
                ]);
            }
        });
        return $catalogPricesAll;
    }
}