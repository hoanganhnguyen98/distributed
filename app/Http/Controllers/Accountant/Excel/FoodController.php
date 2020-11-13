<?php

namespace App\Http\Controllers\Accountant\Excel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Model\BillDetail;
use App\Model\Food;

class FoodController extends Controller implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function collection()
    {
        $current_bill_details = BillDetail::where('status', 'done')->get();

        // get array include food id and number
        $bill_foods = array();
        foreach ($current_bill_details as $current_bill_detail) {
            $a['food_id'] = $current_bill_detail->food_id;
            $a['number'] = $current_bill_detail->number;
            $bill_foods[] = $a;
        }

        // merge same food id and increase number
        for ($i = 0; $i < count($bill_foods) ; $i++) {
            for ($j = $i + 1; $j < count($bill_foods); $j++) {
                if ($bill_foods[$i]['food_id'] == $bill_foods[$j]['food_id']) {
                    $bill_foods[$i]['number'] = $bill_foods[$i]['number'] + $bill_foods[$j]['number'];
                    $bill_foods[$j]['number'] = 0;
                }
            }
        }

        // delete food_id with number = 0
        $bill_mergeds = array();
        foreach ($bill_foods as $bill_food) {
            if ($bill_food['number'] != 0) {
                $bill_mergeds[] = $bill_food;
            }
        }

        // get name of food
        $foods = array();
        foreach ($bill_mergeds as $bill_merged) {
            $food_id = $bill_merged['food_id'];
            $food = Food::where('id', $food_id)->first();

            $food_detail = array();
            $food_detail['food_id'] = $food_id;
            $food_detail['name'] = $food->name;
            $food_detail['number'] = $bill_merged['number'];

            $foods[] = $food_detail;
        }

        array_multisort(array_column($foods, "number"), SORT_DESC, $foods);

        return (collect($foods));
    }

    public function headings(): array
    {
        return [
            'Mã số',
            'Tên món ăn',
            'Số lượt phục vụ',
        ];
    }

    public function export()
    {
        return Excel::download(new FoodController(), 'foods.xlsx');
    }
}
