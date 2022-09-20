<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;

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
}
