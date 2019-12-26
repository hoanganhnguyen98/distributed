<?php

namespace App\Http\Controllers\Accountant\Excel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Model\Bill;

class BillController extends Controller implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function collection()
    {
        $bills = Bill::where('status', 'done')->orderBy('created_at')->get();

        foreach ($bills as $row) {
            $bill[] = array(
                '0' => $row->customer_name,
                '1' => $row->street.'-'.$row->district.'-'.$row->city,
                '2' => $row->phone,
                '3' => $row->email,
                '4' => $row->created_at,
                '5' => $row->table_id,
                '6' => $row->total_price,
            );
        }

        return (collect($bill));
    }

    public function headings(): array
    {
        return [
            'Tên khách hàng',
            'Địa chỉ',
            'Số điện thoại',
            'Thư điện tử',
            'Thời gian',
            'Bàn',
            'Tổng hóa đơn',
        ];
    }

    public function export()
    {
        return Excel::download(new BillController(), 'bills.xlsx');
    }
}
