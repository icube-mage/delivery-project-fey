<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Exports\FileDataExport;
use App\Http\Controllers\Controller;
use App\Models\CatalogPriceAvg;
use App\Models\CatalogPriceTemp;
use App\Models\HistoryUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
        $catalogTemp = CatalogPriceTemp::where('user_id', '=', Auth::user()->id)->get();
        $dataCatalog = [];
        $brand = "";
        $countDataTemp = "";
        $extrasHistory = [];
        $errorIds=[];
        $marketplace = "";
        $totalError = "";
        $userId = "";
        $countError=0;
        $countWhitelist = 0;
        foreach ($catalogTemp as $items) {
            $brand = $items->brand;
            $marketplace = $items->marketplace;
            $userId = $items->user_id;
            $countDataTemp = CatalogPriceTemp::where('sku', $items->sku)
                            ->where('brand', $items->brand)
                            ->where('marketplace', $items->marketplace)->count();
            $totalDiscountPriceTemp = CatalogPriceTemp::where('sku', $items->sku)
                            ->where('brand', $items->brand)
                            ->where('marketplace', $items->marketplace)->sum('discount_price');
            $avgTemp = $totalDiscountPriceTemp / $countDataTemp;
            $averagePrice = CatalogPriceAvg::where('sku', $items->sku)->where('marketplace', $items->marketplace)->where('brand', $items->brand)->where('warehouse', $items->warehouse)->pluck('average_price')->first();
            if ($items->discount_price < ceil($averagePrice) && $items->is_discount==true) {
                CatalogPriceTemp::where('id', $items->id)->update(['is_negative' => true]);
                $dataCatalog[] = array(
                    'id' => $items->id,
                    'sku' => $items->sku,
                    'product_name' => $items->product_name,
                    'price' => $items->discount_price,
                    'discount' => $avgTemp,
                    'average_discount' => $averagePrice,
                    'is_whitelist' => $items->is_whitelist,
                    'is_negative' => true,
                    'is_changed' => false
                );
                $errorIds[] = $items->id;
                $extrasHistory[] = array(
                    'sku' => $items->sku,
                    'price' => $items->discount_price,
                    'average_discount' => $averagePrice
                );
            }
            if ($items->is_whitelist) {
                $countWhitelist++;
            }
        }
        $countError = count($dataCatalog);

        $errorData = $countError-$countWhitelist;
        // session()->forget('historyData');
        // dd(session()->has('historyData'));
        // Insert data to history
        $totalError = $countError;
        if (session()->has('checkPriceHash')) {
            $generateHash = session()->get('checkPriceHash');
        } else {
            $generateHash = Str::uuid();
            session()->put('checkPriceHash', $generateHash);
        }
        if ($totalError>0) {
            HistoryUser::updateOrCreate(
                [
                    'session_hash' => $generateHash,
                    'user_id' => $userId,
                    'brand' => $brand,
                    'marketplace' => $marketplace,
                ],
                [
                    'total_records' => $catalogTemp->count(),
                    'false_price' => $totalError,
                    'extras' => json_encode($extrasHistory)
                ]
            );
        }
        
        return view('pages.menu.uploadfile.checkprice', 
            compact('dataCatalog', 'brand', 'marketplace', 'errorData', 'errorIds'));
    }

    public function export($marketplace, $brand, Request $request)
    {
        $ids = explode(",",$request->data);
        session()->flash('downloaded-excel-after-submit', 'success');
        return Excel::download(new FileDataExport($marketplace, $brand, $ids), 'fey_'.$brand.'_'.$marketplace.'.xlsx');
    }
}
