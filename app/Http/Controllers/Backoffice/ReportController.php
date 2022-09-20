<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
