$( "#tableInput" ).keyup(function() {
    var tableList = $( "#waiterTableList .row" );
    for (var i = 0; i < tableList.length; i++) {
        var table = tableList.eq(i);
        var tableId = table.find("button:eq(0)").html();

        (tableId.indexOf($(this).val()) > -1) ? table.css("display", "") : table.css("display", "none")
    }
});

$(document).ready(function() {
    // set color border for image
    setStatus();

    // init an Pusher object with Pusher app key
    var pusher = new Pusher('6063520d51edaa14b9cf', {
        cluster: 'ap1',
        encrypted: true
    });

    // register a channel created in event
    var channel = pusher.subscribe('channel-display-billing-waiter');

    // bind a function with event
    channel.bind('App\\Events\\DisplayBillingTableInWaiterEvent', changeTableStatus);
});

// function to change status of table
function changeTableStatus(data) {
    var tableList = $( "#waiterTableList .row" );
    for (var i = 0; i < tableList.length; i++) {
        var table = tableList.eq(i);
        var tableId = table.find("button:eq(0)").val();

        if (data.table_id == tableId && data.status == 'run') {
            table.find("img:eq(0)").attr("class", "rounded-circle border border-danger");
            table.find("button:eq(1)").css("display", "inline");
        } else if (data.table_id == tableId && data.status == 'ready') {
            table.find("img:eq(0)").attr("class", "rounded-circle border border-success");
            table.find("button:eq(1)").css("display", "none");
        }
    }
}

function setStatus () {
    var tableList = $( "#waiterTableList .row" );
    for (var i = 0; i < tableList.length; i++) {
        var table = tableList.eq(i);
        var img = table.find("img:eq(0)");
        var altVal = img.attr("alt");

        if (altVal == 'run') {
            img.attr("class", "rounded-circle border border-danger");
            table.find("button:eq(1)").css("display", "inline");
        } else if (altVal == 'ready') {
            img.attr("class", "rounded-circle border border-success");
            table.find("button:eq(1)").css("display", "none");
        }
    }
}
