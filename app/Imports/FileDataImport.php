<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\CatalogPrice;
use App\Models\Configuration;
use App\Models\CatalogPriceTemp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Menu\UploadFile;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class FileDataImport implements ToModel, WithHeadingRow, WithStartRow, WithMultipleSheets, WithCalculatedFormulas
{
    private $brand;
    private $marketplace;
    
    public function __construct(string $brand, string $marketplace) 
    {
        HeadingRowFormatter::default('none');
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
                $getConfigTokped = Configuration::where("key","=",$configName)->first() ? explode(",", Configuration::where("key","=",$configName)->pluck('value')->first()) : null;
                $mapping_field = [];
                foreach($getConfigTokped as $array)
                {
                    $value = explode("=", $array); 
                    $mapping_field[$value[0]] = $value[1];
                }
                $skuConfig = $mapping_field['sku'] ?? null;
                if($skuConfig==null){
                    throw new \Exception ("Sku is not available");
                }
                $nameConfig = $mapping_field['product_name'] ?? null;
                $discountPriceConfig = $mapping_field['discount_price'] ?? null;
                $retailPriceConfig = $mapping_field['retail_price'] ?? null;
                $startDateConfig = $mapping_field['start_date'] ?? null;
                break;
            case 'shopee':
                $configName = $this->marketplace."_column_map";
                $getConfigShopee = Configuration::where("key","=",$configName)->first() ? explode(",", Configuration::where("key","=",$configName)->pluck('value')->first()) : null;
                $mapping_field = [];
                foreach($getConfigShopee as $array)
                {
                    $value = explode("=", $array); 
                    $mapping_field[$value[0]] = $value[1];
                }
                $skuConfig = $mapping_field['sku'] ?? null;
                if($skuConfig==null){
                    throw new \Exception ("Sku is not available");
                }
                $nameConfig = $mapping_field['product_name'] ?? null;
                $discountPriceConfig = $mapping_field['discount_price'] ?? null;
                $retailPriceConfig = $mapping_field['retail_price'] ?? null;
                $startDateConfig = $mapping_field['start_date'] ?? null;
                break;
            case 'lazada':
                $configName = $this->marketplace."_column_map";
                $getConfigLazada = Configuration::where("key","=",$configName)->first() ? explode(",", Configuration::where("key","=",$configName)->pluck('value')->first()) : null;
                $mapping_field = [];
                foreach($getConfigLazada as $array)
                {
                    $value = explode("=", $array); 
                    $mapping_field[$value[0]] = $value[1];
                }
                $skuConfig = $mapping_field['sku'] ?? null;
                if($skuConfig==null){
                    throw new \Exception ("Sku is not available");
                }
                $nameConfig = $mapping_field['product_name'] ?? null;
                $discountPriceConfig = $mapping_field['discount_price'] ?? null;
                $retailPriceConfig = $mapping_field['retail_price'] ?? null;
                $startDateConfig = $mapping_field['start_date'] ?? null;
                break;
            case 'bukalapak':
                $configName = $this->marketplace."_column_map";
                $getConfigBukalapak = Configuration::where("key","=",$configName)->first() ? explode(",", Configuration::where("key","=",$configName)->pluck('value')->first()) : null;
                $mapping_field = [];
                foreach($getConfigBukalapak as $array)
                {
                    $value = explode("=", $array); 
                    $mapping_field[$value[0]] = $value[1];
                }
                $skuConfig = $mapping_field['sku'] ?? null;
                if($skuConfig==null){
                    throw new \Exception ("Sku is not available");
                }
                $nameConfig = $mapping_field['product_name'] ?? null;
                $discountPriceConfig = $mapping_field['discount_price'] ?? null;
                $retailPriceConfig = $mapping_field['retail_price'] ?? null;
                $startDateConfig = $mapping_field['start_date'] ?? null;
                break;
                
            default:
                $msg = "Marketplace not registered";
                break;
        }

        try{
            $sku = $row[$skuConfig];
            $discountPrice = $row[$discountPriceConfig];
        } catch(\Exception $e){
            dd($row);
            throw new \Exception ($e->getMessage());
        }

        $retailPrice = null;
        $startDateOriginal = null;

        try{
            $name = $row[$nameConfig];
            $retailPrice = $row[$retailPriceConfig] ?? 0;
            $startDateOriginal = $row[$startDateConfig] ?? date('d-m-Y');
        } catch(\Exception $e){
        }

        
        $name = $name ?? "No Name";
        $retailPrice = $retailPrice == "#N/A" ? str_replace("#N/A", 0, $retailPrice) : $retailPrice;

        $discountPrice = $discountPrice == "#N/A" ? str_replace("#N/A", 0, $discountPrice) : $discountPrice;

        $startDateOriginal = $startDateOriginal ? str_replace('/', '-', $startDateOriginal) : $startDateOriginal;
        $startDate = date('Y-m-d', strtotime($startDateOriginal));

        if($sku == null){
            // dd($sku);
            throw new \Exception ("Please check SKU");
        } elseif($discountPrice == null){
            throw new \Exception ("Please check Discount Price");
        }else{
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

    public function sheets(): array
    {
        return [
            0 => $this
        ];
    }
}