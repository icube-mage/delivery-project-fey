<?php 
namespace App\Exports;

use App\Models\CatalogPrice;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CatalogPriceExport implements FromCollection,WithHeadings
{
    public function __construct($filter = null)
    {
        $this->filter = $filter;
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
        $filter = '%'.$this->filter.'%';
        $catalogPricesAll = collect([]);
        CatalogPrice::select('marketplace', 'brand', 'sku', 'product_name', 'retail_price','discount_price','users.name', 'catalog_prices.created_at as date')
        ->leftJoin('users', 'catalog_prices.user_id', '=', 'users.id')
        ->where(function($query) use($filter){
            $query->where('brand', 'like', $filter)
                ->orWhere('marketplace', 'like', $filter)
                ->orWhere('upload_hash', 'like', $filter)
                ->orWhereHas('user', function ($sub_query) use($filter) {
                    $sub_query->where('name', 'like', $filter);
                });
        })
        ->chunk(10000, function ($catalogPrices) use($catalogPricesAll){
            foreach ($catalogPrices as $catalogPrice) {
                $catalogPricesAll->push([
                    "marketplace"=>$catalogPrice->marketplace,
                    "brand"=>$catalogPrice->brand,
                    "sku"=>$catalogPrice->sku,
                    "product_name"=>$catalogPrice->product_name,
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