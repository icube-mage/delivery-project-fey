<?php 
namespace App\Exports;

use App\Models\CatalogPrice;
use App\Models\HistoryUser;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class HistoryUserExport implements FromCollection,WithHeadings
{
    public function headings():array{
        return[
            'User',
            'Marketplace',
            'brand',
            'Total Record',
            'Wrong Price'
        ];
    } 
    public function collection()
    {
        return HistoryUser::select('users.name', 'marketplace', 'brand', 'total_records', 'false_price')
        ->leftJoin('users', 'history_users.user_id', '=', 'users.id')
        ->get();
    }
}