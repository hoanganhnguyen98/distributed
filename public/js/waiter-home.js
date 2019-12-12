$( "#tableInput" ).keyup(function() {
	var tableList = $( "#waiterTableList .row" );
    for (var i = 0; i < tableList.length; i++) {
    	var table = tableList.eq(i);
        var tableId = table.find("button:eq(0)").html();

        (tableId.indexOf($(this).val()) > -1) ? table.css("display", "") : table.css("display", "none")
    }
});
