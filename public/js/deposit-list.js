// filter by user id
$("#userIDInput").keyup(function() {
	// get data table to filter
	var table = $("#todayDeposit tr");
    // compare with user ids in table
    for (var i = 0; i < table.length; i++) {
        var row = table.eq(i);
        var userID = row.find("td:eq(0)").html().toUpperCase();

        (userID.indexOf($(this).val().toUpperCase()) > -1 ? row.css("display", "") : row.css("display", "none"))
    }
});
