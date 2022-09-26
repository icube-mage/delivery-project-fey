<?php

namespace App\Imports;

use App\Models\Configuration;
use App\Models\CatalogPriceTemp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class FileDataImport implements ToModel, WithHeadingRow, WithStartRow, WithMultipleSheets, WithCalculatedFormulas
{
    private $brand;
    private $marketplace;
    private $headingRow;
    private $startRow;
    
    public function __construct(string $brand, string $marketplace) 
    {
        HeadingRowFormatter::default('none');
        $this->brand = $brand;
        $this->marketplace = $marketplace;
        $configuration = Configuration::where('key', $this->marketplace.'_row_map')->first();

        if($configuration) {
            $mapping_field = [];
            $getConfig = explode(",", $configuration->value);
            foreach($getConfig as $array)
            {
                $value = explode("=", $array); 
                $mapping_field[$value[0]] = $value[1];
            }
            $this->headingRow = $mapping_field['heading'] ?? 1;
            $this->startRow = $mapping_field['content'] ?? 2;
        } else {
            $this->headingRow = 2;
            $this->startRow = 4;
        }
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
                    throw new \Exception ("Sku config is not available");
                }
                $warehouseConfig = $mapping_field['warehouse'] ?? null;
                if($warehouseConfig==null){
                    throw new \Exception ("Warehouse config is not available");
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
                throw new \Exception ("Marketplace not registered");
                break;
        }

        try{
            $sku = $row[$skuConfig];
            $discountPrice = $row[$discountPriceConfig];
            $warehouse = $row[$warehouseConfig];
        } catch(\Exception $e){
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
        if (Str::contains($retailPrice, 'N/A')) {
            $retailPrice = str_replace("N/A", '', $retailPrice);
            $retailPrice = str_replace("#", '', $retailPrice);
            $retailPrice = ($retailPrice=='' || $retailPrice==null) ? 0 : str_replace($retailPrice, 0, $retailPrice);
        } else {
            $retailPrice = $retailPrice;
        }
        if(Str::contains($discountPrice, 'N/A')){
            $discountPrice = str_replace("N/A", 0, $discountPrice);
            $discountPrice = str_replace("#", 0, $discountPrice);
            $discountPrice = ($discountPrice=='' || $discountPrice==null) ? 0 : str_replace($discountPrice, 0, $discountPrice);
            $is_discount = false;
        } else {
            $discountPrice = $discountPrice;
            $is_discount = true;
        }

        $startDateOriginal = $startDateOriginal ? str_replace('/', '-', $startDateOriginal) : $startDateOriginal;
        $startDate = date('Y-m-d', strtotime($startDateOriginal));

        if($sku == null){
            throw new \Exception ("Please check SKU column");
        } elseif($warehouse == null){
            throw new \Exception ("Please check Warehuse column");
        } elseif($discountPrice == null){
            throw new \Exception ("Please check Discount Price column");
        }else{
            $catalogPriceTemp = [
                'sku'  => $sku,
                'product_name'  => $name,
                'retail_price' => $retailPrice,
                'discount_price' => $discountPrice,
                'user_id' => Auth::user()->id,
                'brand' => $this->brand,
                'marketplace' => $this->marketplace,
                'warehouse' => $warehouse,
                'start_date' => $startDate,
            ];
            if($is_discount==false){
                $catalogPriceTemp['is_discount'] = false;
            }
            $createCatalogPriceTemp = new CatalogPriceTemp($catalogPriceTemp);
            return $createCatalogPriceTemp;
        }
    }

    public function headingRow(): int
    {
        return $this->headingRow;
    }

    /**
    * @return int
    */
    public function startRow(): int
    {
        return $this->startRow;
    }

    public function sheets(): array
    {
        return [
            0 => $this
        ];
    }
}