<?php

namespace App\Imports;

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
        DB::beginTransaction();
        $catalogTemp = CatalogPriceTemp::all();
        if($catalogTemp->isNotEmpty()){
            DB::table('catalog_price_temps')->truncate();
        }
        // dd($catalogTemp);
        // Hapus data temp
        $sku = isset($row['nama_sku']) ? $row['nama_sku'] : 0;
        $name = isset($row['nama_produk']) ? $row['nama_produk'] : 0;
        $rrp = isset($row['harga']) ? (int)$row['harga'] : 0;
        $cbp = isset($row['harga_diskon_rp']) == "#N/A" ? str_replace("#N/A", 0, $row['harga_diskon_rp']) : (isset($row['harga_diskon_rp']) ? $row['harga_diskon_rp'] : 0);
        $startDateOriginal = isset($row['tanggal_mulai']) ? $row['tanggal_mulai'] : null;
        $startDate = date('Y-d-m', strtotime($startDateOriginal));
        // dd($startDate);
        if($sku != 0 || $name != 0 || $rrp != 0 || $cbp != 0){
            $createCatalogPriceTemp = new CatalogPriceTemp([
                'sku'  => $sku,
                'name'  => $name,
                'rrp' => $rrp,
                'cbp'    => $cbp,
                'user_id' => Auth::user()->id,
                'brand' => $this->brand,
                'marketplace' => $this->marketplace,
                'start_date' => $startDate ? $startDate : 0,
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