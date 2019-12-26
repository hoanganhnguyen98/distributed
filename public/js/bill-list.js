// display sidebar after clicking
$("#billSidebar").attr("class", "collapse show list-unstyled");

// get table of bill to filter by columns
var billTable = $("#billTable tr");

// filter by id
$("#idInput").keyup(function() {
    for (var i = 0; i < billTable.length; i++) {
        var tr = billTable.eq(i);
        var id = tr.find("td:eq(0)").html().toUpperCase();

        (id.indexOf($(this).val().toUpperCase()) > -1 ? tr.css("display", "") : tr.css("display", "none"))
    }
});

// filter by table
$("#tableInput").keyup(function() {
    for (var i = 0; i < billTable.length; i++) {
        var tr = billTable.eq(i);
        var table = tr.find("td:eq(1)").html().toUpperCase();

        (table.indexOf($(this).val().toUpperCase()) > -1 ? tr.css("display", "") : tr.css("display", "none"))
    }
});

// filter by name
$("#nameInput").keyup(function() {
    for (var i = 0; i < billTable.length; i++) {
        var tr = billTable.eq(i);
        var name = tr.find("td:eq(2)").html().toUpperCase();

        (name.indexOf($(this).val().toUpperCase()) > -1 ? tr.css("display", "") : tr.css("display", "none"))
    }
});

// filter by phone
$("#phoneInput").keyup(function() {
    for (var i = 0; i < billTable.length; i++) {
        var tr = billTable.eq(i);
        var phone = tr.find("td:eq(3)").html().toUpperCase();

        (phone.indexOf($(this).val().toUpperCase()) > -1 ? tr.css("display", "") : tr.css("display", "none"))
    }
});

// filter by book time
$("#bookInput").keyup(function() {
    for (var i = 0; i < billTable.length; i++) {
        var tr = billTable.eq(i);
        var book = tr.find("td:eq(4)").html().toUpperCase();

        (book.indexOf($(this).val().toUpperCase()) > -1 ? tr.css("display", "") : tr.css("display", "none"))
    }
});

// filter by pay time
$("#payInput").keyup(function() {
    for (var i = 0; i < billTable.length; i++) {
        var tr = billTable.eq(i);
        var pay = tr.find("td:eq(5)").html().toUpperCase();

        (pay.indexOf($(this).val().toUpperCase()) > -1 ? tr.css("display", "") : tr.css("display", "none"))
    }
});
