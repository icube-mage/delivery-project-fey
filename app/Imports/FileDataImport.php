<?php

namespace App\Imports;

use App\Models\CatalogPriceTemp;
use App\Http\Livewire\Menu\UploadFile;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FileDataImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new CatalogPriceTemp([
            'sku'  => $row['nama_sku'],
            'rrp' => $row['harga'],
            'cbp'    => $row['harga_diskon'],
            'user_id' => UploadFile::userId,
            'brand' => UploadFile::brand,
            'marketplace' => UploadFile::marketplace
        ]);
    }
}
