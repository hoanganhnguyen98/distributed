<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Ninja Restaurant</title>

        <style type="text/css">
        </style>
    </head>
    <body>
        <div>
            <div style="float: left; width: 30%; text-align: center;">
                <img src="https://res.cloudinary.com/ninjahh/image/upload/v1575620163/ninja_restaurant/x8tpmyihyvxav2tmrybw.jpg" width="100" height="100" />
            </div>
            <div style="float: right; width: 70%; text-align: center; color: #008800;">
                <p>NINJA RESTAURANT</p>
                <p>51 floor, Bitexco Buidling, 2, Hai Trieu</p>
                <p>Ben Nghe, 1 District, Ho Chi Minh City</p>
                <p>(+84)363866499 - hoanghuynh1998@gmail.com</p>
            </div>
        </div>
        <p style="border-top: 1px solid;"></p>
        <p style="text-align: center; font-size: 2em; color: #DD0000">INVOICE</p>
        <div style="border-bottom: 1px solid;">
            <div style="float: left; width: 50%; text-align: left;">
                <p>Receptionist: {{ $user-> name }}</p>
                <p>Area: {{ $user->area }}</p>
            </div>
            <div style="float: right; width: 50%; text-align: left;">
                <p>Bill number: {{ $bill->id }}</p>
                <p>Time: {{ $now }}</p>
            </div>
        </div>

        <div style="border-bottom: 1px solid;">
            <div style="float: left; width: 50%;">
                <table>
                    <tr>
                        <td>{{ $bill->customer_name }}</td>
                    </tr>
                    <tr>
                        <td>{{ $bill->street }} - {{ $bill->district }} - {{ $bill->city }}</td>
                    </tr>
                    <tr>
                        <td>
                            <img src="https://res.cloudinary.com/ninjahh/image/upload/v1575623001/ninja_restaurant/dhrvhexyhwe4xyk6oed7.png" width="200" height="80" />
                        </td>
                    </tr>
                </table>
            </div>

            <div style="float: right; width: 50%;">
                <table>
                    @foreach($bill_details as $bill_detail)
                    <tr>
                        <td style="width: 50%">{{ $bill_detail['name'] }}</td>
                        <td style="width: 10%; text-align: right;">{{ $bill_detail['number'] }}</td>
                        <td style="width: 20%; text-align: right;">{{ $bill_detail['vnd_price'] }}</td>
                        <td style="width: 30%; text-align: right;">{{ $bill_detail['vnd_total'] }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="width: 50%; color: #DD0000">TOTAL PRICE</td>
                        <td style="width: 10%"></td>
                        <td style="width: 20%">=</td>
                        <td style="width: 30%; text-align: right;">{{ $vndPrice }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <p style="text-align: center;">Xin chào và hẹn gặp lại - Goodbye and see you again!</p>
        <p style="text-align: center;">Office: 538, Tran Hung Dao Street, Hoa Vuong District, Nam Dinh City</p>
        <p style="text-align: center;">https://ninja-restaurant.herokuapp.com/index</p>
    </body>
</html>
