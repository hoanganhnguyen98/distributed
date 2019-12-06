<table class="table">
    <thead>
        <tr class="text-primary">
            <th scope="col">Food name</th>
            <th scope="col" class="text-center">Number</th>
            <th scope="col" class="text-right">VND</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bill_details as $bill_detail)
        <tr>
            <td>{{ $bill_detail['name'] }}</td>
            <td class="text-center">{{ $bill_detail['number'] }}</td>
            <td class="text-right">{{ $bill_detail['vnd_price'] }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2" class="text-danger font-weight-bold">Total price</td>
            <td class="text-right">{{ $vndPrice }}</td>
        </tr>
    </tbody>
</table>
