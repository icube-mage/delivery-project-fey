<?php 
namespace App\Exports;

use App\Models\User;
use App\Models\Student;
use App\Models\HistoryUser;
use App\Models\CatalogPrice;
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
            'Wrong Price',
            'Created At'
        ];
    } 
    public function collection()
    {
        return User::select('name', 'history_users.marketplace', 'history_users.brand', 'history_users.total_records', 'history_users.false_price', 'history_users.created_at')
        ->leftJoin('history_users', 'users.id', '=', 'history_users.user_id')
        ->whereHas('roles', function($query){
            $query->where('name', 'Store Operations');
        })->get();
    }
}