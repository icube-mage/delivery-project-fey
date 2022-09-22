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
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class FileDataImport implements ToModel, WithHeadingRow, WithStartRow
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
                    throw new \Exception ("Sku kosong");
                }
                $nameConfig = $mapping_field['product_name'] ?? null;
                $discountPriceConfig = $mapping_field['discount_price'] ?? null;
                $retailPriceConfig = $mapping_field['retail_price'] ?? null;
                $startDateConfig = $mapping_field['start_date'] ?? null;
                
                // $nameData = end($nameConfig);
                // $skuData = end($skuConfig);
                // $discountPriceData = end($discountPriceConfig);
                // $retailPriceData = end($retailPriceConfig);
                // $startDateData = end($startDateConfig) ? end($startDateConfig) : null;

            // case 'shopee':
            //     $configName = $this->marketplace."_column_map";
            //     $getConfigTokped = explode(",", Configuration::where("key","=",$configName)->pluck('value')->first());
                
            //     $skuConfig = array_values(explode("=",$getConfigTokped[0]));
            //     $nameConfig = array_values(explode("=",$getConfigTokped[1]));
            //     $discountPriceConfig = array_values(explode("=",$getConfigTokped[2]));
            //     $retailPriceConfig = array_values(explode("=",$getConfigTokped[3]));
            //     $startDateConfig = array_values(explode("=",$getConfigTokped[4]));
                
            //     $nameData = end($nameConfig);
            //     $skuData = end($skuConfig);
            //     $discountPriceData = end($discountPriceConfig);
            //     $retailPriceData = end($retailPriceConfig);
            //     $startDateData = end($startDateConfig);
            // case 'lazada':
            //     $configName = $this->marketplace."_column_map";
            //     $getConfigTokped = explode(",", Configuration::where("key","=",$configName)->pluck('value')->first());
                
            //     $skuConfig = array_values(explode("=",$getConfigTokped[0]));
            //     $nameConfig = array_values(explode("=",$getConfigTokped[1]));
            //     $discountPriceConfig = array_values(explode("=",$getConfigTokped[2]));
            //     $retailPriceConfig = array_values(explode("=",$getConfigTokped[3]));
            //     $startDateConfig = array_values(explode("=",$getConfigTokped[4]));
                
            //     $nameData = end($nameConfig);
            //     $skuData = end($skuConfig);
            //     $discountPriceData = end($discountPriceConfig);
            //     $retailPriceData = end($retailPriceConfig);
            //     $startDateData = end($startDateConfig);
            // case 'bukalapak':
            //     $configName = $this->marketplace."_column_map";
            //     $getConfigTokped = explode(",", Configuration::where("key","=",$configName)->pluck('value')->first());
                
            //     $skuConfig = array_values(explode("=",$getConfigTokped[0]));
            //     $nameConfig = array_values(explode("=",$getConfigTokped[1]));
            //     $discountPriceConfig = array_values(explode("=",$getConfigTokped[2]));
            //     $retailPriceConfig = array_values(explode("=",$getConfigTokped[3]));
            //     $startDateConfig = array_values(explode("=",$getConfigTokped[4]));
                
            //     $nameData = end($nameConfig);
            //     $skuData = end($skuConfig);
            //     $discountPriceData = end($discountPriceConfig);
            //     $retailPriceData = end($retailPriceConfig);
            //     $startDateData = end($startDateConfig);
            break;
                
            default:
                $msg = "Marketplace not registered";
            break;
        }

        try{
            $sku = $row[$skuConfig];
            $discountPrice = $row[$discountPriceConfig];
        } catch(\Exception $e){
            throw new \Exception ($e->getMessage());
        }

        try{
            $name = $row[$nameConfig];
            $retailPrice = $row[$retailPriceConfig];
            $startDateOriginal = $row[$startDateConfig];
        } catch(\Exception $e){

        }

        $name = $name ?? "No Name";
        $retailPrice = $retailPrice == "#N/A" ? str_replace("#N/A", 0, $retailPrice) : $retailPrice;

        $discountPrice = $discountPrice == "#N/A" ? str_replace("#N/A", 0, $discountPrice) : $discountPrice;

        $startDateOriginal = $startDateOriginal ? str_replace('/', '-', $startDateOriginal) : date('d-m-Y');
        $startDate = date('Y-m-d', strtotime($startDateOriginal));

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

    // public function rules(): array
    // {
    //     $configName = "tokopedia_column_map";
    //     $getConfigTokped = Configuration::where("key","=",$configName)->first() ? explode(",", Configuration::where("key","=",$configName)->pluck('value')->first()) : null;
    //     $mapping_field = [];
    //     foreach($getConfigTokped as $array)
    //     {
    //         $value = explode("=", $array); 
    //         $mapping_field[$value[0]] = $value[1];
    //     }
    //     $skuConfig = $mapping_field['sku'] ?? null;
    //     $nameConfig = $mapping_field['product_name'] ?? null;
    //     $discountPriceConfig = $mapping_field['discount_price'] ?? null;
    //     $retailPriceConfig = $mapping_field['retail_price'] ?? null;
    //     $startDateConfig = $mapping_field['start_date'] ?? null;
    //     return [
    //         "2.".$skuConfig => function($attribute, $value, $onFailure) {
    //             $onFailure($attribute);
    //             if ($value !== 'Patrick Brouwers') {
    //                  $onFailure('Name is not Patrick Brouwers');
    //             }
    //         },
    //     ];
    // }
}