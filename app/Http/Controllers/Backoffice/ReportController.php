<?php

namespace App\Http\Controllers\Backoffice;

use App\Exports\HistoryUserExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('pages.menu.report.catalog-price');
    }

    public function show($user)
    {
        return view('pages.menu.report.catalog-price-history', compact('report'));
    }

    public function export()
    {
        return Excel::download(new HistoryUserExport, 'history_user_'.date('Y-m-d H:i:s').'.xlsx');
    }
}
