// $( "input" ).keyup(function() {
//     // each tr in table
//     for (var i = 0; i < $( "#todayDeposit tr" ).length; i++) {
//         // get input of vnd and usd, fourth and fifth <input> tag
//         var vndFinal = $( "#todayDeposit tr:eq("+i+") td:eq(1) input:eq(4)" ).val();
//         var usdFinal = $( "#todayDeposit tr:eq("+i+") td:eq(1) input:eq(5)" ).val();

//         // if input not null, display button
//         var button = $( "#todayDeposit tr:eq("+i+") td:eq(1) button:eq(0)" );
//         (vndFinal.length != 0 && usdFinal.length != 0 ? button.css("display", "inline") : button.css("display", "none"))
//     }
// });

// filter by user id
$( "#userIDInput" ).keyup(function() {
    // get input to filter
    var userIDInput = $(this).val();

    // compare with user ids in table
    for (var i = 0; i < $( "#todayDeposit tr" ).length; i++) {
        // get each user id in table
        var userID = $( "#todayDeposit tr:eq("+i+") td:eq(0)" ).html();
        var tr = $( "#todayDeposit tr:eq("+i+")" );

        (userID.indexOf(userIDInput) > -1 ? tr.css("display", "") : tr.css("display", "none"))
    }
});
