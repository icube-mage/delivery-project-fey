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
    public function index()
    {
        return view('pages.menu.uploadfile');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkPrice()
    {
        return view('pages.menu.uploadfile.checkprice');
    }

    public function export($marketplace, $brand, Request $request)
    {
        $dataTemps = json_decode($request->data);
        $ids = [];
        foreach($dataTemps as $data){
            $ids[] = $data->id;
        }
        session()->flash('downloaded-excel-after-submit', 'success');
        return Excel::download(new FileDataExport($marketplace, $brand, $ids), 'fey_'.$brand.'_'.$marketplace.'.xlsx');
    }
}
