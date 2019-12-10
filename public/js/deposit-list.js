$( "input" ).keyup(function() {
    for (var i = 0; i < $( "#todayDeposit tr" ).length; i++) {
        var vndFinal = $( "#todayDeposit tr:eq("+i+") th:eq(1) input:eq(2)" ).val();
        var usdFinal = $( "#todayDeposit tr:eq("+i+") th:eq(1) input:eq(3)" ).val();

        if (vndFinal.length != 0 && usdFinal.length != 0) {
            $( "#todayDeposit tr:eq("+i+") th:eq(1) button:eq(0)" ).css("display", "inline");
        } else {
            $( "#todayDeposit tr:eq("+i+") th:eq(1) button:eq(0)" ).css("display", "none");
        }
    }
});