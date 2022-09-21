<?php

namespace App\Imports;

use App\Models\CatalogPrice;
use App\Models\Configuration;
use App\Models\CatalogPriceTemp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Menu\UploadFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FileDataImport implements ToModel, WithHeadingRow, WithStartRow
{
    private $brand;
    private $marketplace;

    public function __construct(string $brand, string $marketplace) 
    {
        $this->brand = $brand;
        $this->marketplace = $marketplace;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        switch($this->marketplace){
            case 'tokopedia':
                $configName = $this->marketplace."_column_map";
                $getConfigTokped = explode(",", Configuration::where("key","=",$configName)->pluck('value')->first());
                
                $nameConfig = array_values(explode("=",$getConfigTokped[0]));
                $skuConfig = array_values(explode("=",$getConfigTokped[1]));
                $discountPriceConfig = array_values(explode("=",$getConfigTokped[2]));
                $retailPriceConfig = array_values(explode("=",$getConfigTokped[3]));
                $startDateConfig = array_values(explode("=",$getConfigTokped[4]));
                
                $nameData = end($skuConfig);
                $skuData = end($skuConfig);
                $discountPriceData = end($discountPriceConfig);
                $retailPriceData = end($retailPriceConfig);
                $startDateData = end($startDateConfig);
            break;
                
            default:
                $msg = "Marketplace not registered";
            break;
        }
            
            // dd($row[$discountPriceData]);
            // Delete data temp
        $sku = isset($row[$skuData]) ? $row[$skuData] : 0;

        $name = isset($row[$nameData]) ? $row[$nameData] : 0;

        $retailPrice = isset($row[$retailPriceData]) == "#N/A" ? str_replace("#N/A", 0, $row[$retailPriceData]) : (isset($row[$retailPriceData]) ? $row[$retailPriceData] : 0);

        $discountPrice = isset($row[$discountPriceData]) == "#N/A" ? str_replace("#N/A", 0, $row[$discountPriceData]) : (isset($row[$discountPriceData]) ? $row[$discountPriceData] : 0);

        $startDateOriginal = isset($row[$startDateData]) ? str_replace('/', '-', $row[$startDateData]) : date('d-m-Y');
        $startDate = date('Y-m-d', strtotime($startDateOriginal));

        if($sku != 0 || $name != 0 || $discountPrice != 0 || $retailPrice != 0){
            $createCatalogPriceTemp = new CatalogPriceTemp([
                'sku'  => $sku,
                'product_name'  => $name,
                'retail_price' => $retailPrice,
                'discount_price' => $discountPrice,
                'user_id' => Auth::user()->id,
                'brand' => $this->brand,
                'marketplace' => $this->marketplace,
                'start_date' => $startDate,
            ]);
            return $createCatalogPriceTemp;
        }
    }

    public function headingRow(): int
    {
        return 2;
    }

    /**
    * @return int
    */
    public function startRow(): int
    {
        return 4;
    }
}