<?php

namespace App\Imports;

use App\Models\CatalogPrice;
use App\Models\Configuration;
use App\Models\CatalogPriceTemp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Menu\UploadFile;
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
        switch($this->marketplace){
            case 'tokopedia':
                $configName = $this->marketplace."_column_map";
                $getConfigTokped = explode(",", Configuration::where("key","=",$configName)->pluck('value')->first());

                $skuConfig = array_values(explode("=",$getConfigTokped[0]));
                $discountPriceConfig = array_values(explode("=",$getConfigTokped[1]));

                $skuName = end($skuConfig);
                $discountPriceName = end($discountPriceConfig);
                break;
            default:
                $msg = "Marketplace not registered";
                break;
        }
        
        // Delete data temp
        $sku = isset($row[$skuName]) ? $row[$skuName] : 0;
        $name = isset($row['nama_produk']) ? $row['nama_produk'] : 0;
        $discountPrice = isset($row[$discountPriceName]) == "#N/A" ? str_replace("#N/A", 0, $row[$discountPriceName]) : (isset($row[$discountPriceName]) ? $row[$discountPriceName] : 0);
        $startDateOriginal = isset($row['tanggal_mulai']) ? $row['tanggal_mulai'] : null;
        $changeFormatDate = str_replace('/', '-', $startDateOriginal); 
        $startDate = date('Y-m-d', strtotime($changeFormatDate));

        if($sku != 0 || $name != 0 || $discountPrice != 0){
            $createCatalogPriceTemp = new CatalogPriceTemp([
                'sku'  => $sku,
                'name'  => $name,
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