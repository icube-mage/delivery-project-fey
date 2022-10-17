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

        if($this->headingRow == $this->startRow){
            throw new \Exception ("Please check Heading Start & Content Start again on Configuration");
        }
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $mapping_field = $this->getConfig($this->marketplace);
        $skuConfig = $mapping_field['sku'] ?? null;
        if($skuConfig==null){
            throw new \Exception ("Sku config is not available");
        }
        $nameConfig = $mapping_field['product_name'] ?? null;
        $discountPriceConfig = $mapping_field['discount_price'] ?? null;
        $retailPriceConfig = $mapping_field['retail_price'] ?? null;
        $startDateConfig = $mapping_field['start_date'] ?? null;
        $warehouseConfig = $mapping_field['warehouse'] ?? null;

        try{
            $sku = $row[$skuConfig];
            $discountPrice = $row[$discountPriceConfig];
            $name = $row[$nameConfig];
        } catch(\Exception $e){
            throw new \Exception ($e->getMessage());
        }

        $retailPrice = $row[$retailPriceConfig] ?? 0;
        $startDateOriginal = $row[$startDateConfig] ?? date('d-m-Y');
        $warehouse = $row[$warehouseConfig] ?? null;

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
        } elseif($discountPrice === null){
            throw new \Exception ("Please check Discount Price column");
        } elseif($name === null){
            throw new \Exception ("Please check Product Name column");
        } else{
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

    protected function getConfig($marketplace){
        $configuration = Configuration::where('key', $marketplace.'_column_map')->first();

        if($configuration) {
            $mapping_field = [];
            $getConfig = explode(",", $configuration->value);
            foreach($getConfig as $array)
            {
                $value = explode("=", $array); 
                $mapping_field[$value[0]] = $value[1];
            }
            
        }
        return $mapping_field;
    }
}