<table class="table">
    <thead>
        <tr class="text-primary">
            <th scope="col">{{ trans('messages.pay.food_name') }}</th>
            <th scope="col" class="text-center">{{ trans('messages.pay.number') }}</th>
            <th scope="col" colspan="2" class="text-center">{{ trans('messages.pay.vnd') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bill_details as $bill_detail)
        <tr>
            <td>{{ $bill_detail['name'] }}</td>
            <td class="text-center">{{ $bill_detail['number'] }}</td>
            <td class="text-right">* {{ $bill_detail['vnd_price'] }}</td>
            <td class="text-right">= {{ $bill_detail['vnd_total'] }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3" class="text-danger font-weight-bold">{{ trans('messages.pay.total') }}</td>
            <td class="text-danger font-weight-bold text-right" id="totalBillPrice">{{ $vndPrice }}</td>
        </tr>
    </tbody>
</table>
