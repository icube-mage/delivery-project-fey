<?php

namespace App\Http\Controllers\Backoffice;

use App\Exports\CatalogPriceDetailExport;
use App\Exports\CatalogPriceExport;
use App\Http\Controllers\Controller;
use App\Models\CatalogPrice;
use Maatwebsite\Excel\Facades\Excel;

class HistoricalDataController extends Controller
{
    public function index()
    {
        return view('pages.menu.historydata.catalog-price');
    }

    public function show($hash)
    {
        return view('pages.menu.historydata.catalog-price-history', compact('hash'));
    }

    public function exportAll()
    {
        return Excel::download(new CatalogPriceExport, 'historical_log.xlsx');
    }

    public function exportByHash($hash)
    {
        $log = CatalogPrice::where('upload_hash', $hash)->first();
        return Excel::download(new CatalogPriceDetailExport($hash), $log->brand.'_log_'.$hash.'.xlsx');
    }
}
