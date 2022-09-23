<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Exports\FileDataExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class UploadFileController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('pages.menu.uploadfile');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkPrice(Request $request)
    {
        return view('pages.menu.uploadfile.checkprice');
    }

    public function export()
    {
        $date = date('Ymd');
        return Excel::download(new FileDataExport, 'fey_'.$date.'.xlsx');
    }
}
