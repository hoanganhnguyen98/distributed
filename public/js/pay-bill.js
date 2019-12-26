// show form to edit bill information
$("#editBill").click(function(){
    // display form to edit
    $("#name").removeAttr("readonly");
    $("#street").removeAttr("readonly");
    $("#district").removeAttr("readonly");
    $("#city").removeAttr("readonly");
    $("#phone").removeAttr("readonly");
    $("#email").removeAttr("readonly");

    // hidden function buttons
    $(this).css("display", "none");
    $("#enterTotalPrice").css("display", "none");
    $("#totalPriceSuggest").css("display", "none");

    $("#payVNDButton").css("display", "none");
    $("#payUSDButton").css("display", "none");
    $("#checkTrue").css("display", "none");
    $("#checkFalse").css("display", "none");

    // display cancel and submit button
    $("#cancelEditButton").css("display", "inline");
    $("#editBillButton").css("display", "inline");
});

// cancel edit bill information
$("#cancelEditButton").click(function(){
    // hidden form
    $("#name").attr("readonly", '');
    $("#street").attr("readonly", '');
    $("#district").attr("readonly", '');
    $("#city").attr("readonly", '');
    $("#phone").attr("readonly", '');
    $("#email").attr("readonly", '');

    pay();

    // display function buttons
    $("#editBill").css("display", "inline");
    $("#enterTotalPrice").css("display", "inline");
    $("#totalPriceSuggest").css("display", "inline");

    // hidden cancel and submit button
    $(this).css("display", "none");
    $("#editBillButton").css("display", "none");
});

// enter true total price to display pay button
$("#enterTotalPrice").keyup(function(){
    pay();
});

function pay() {
    if ($("#enterTotalPrice").val().length > 0) {
        if ($("#enterTotalPrice").val() == $("#totalVNDPrice").html()) {
            $("#payVNDButton").css("display", "inline");
            $("#payUSDButton").css("display", "none");
            $("#checkTrue").css("display", "inline");
            $("#checkFalse").css("display", "none");

            $("#enterTotalPrice").attr("class", "form-control border border-success");
        } else if($("#enterTotalPrice").val() == $("#totalUSDPrice").html()) {
            $("#payVNDButton").css("display", "none");
            $("#payUSDButton").css("display", "inline");
            $("#checkTrue").css("display", "inline");
            $("#checkFalse").css("display", "none");

            $("#enterTotalPrice").attr("class", "form-control border border-success");
        } else {
            $("#payVNDButton").css("display", "none");
            $("#payUSDButton").css("display", "none");
            $("#checkTrue").css("display", "none");
            $("#checkFalse").css("display", "inline");

            $("#enterTotalPrice").attr("class", "form-control border border-danger");
        }
    } else {
        $("#payVNDButton").css("display", "none");
        $("#payUSDButton").css("display", "none");
        $("#checkTrue").css("display", "none");
        $("#checkFalse").css("display", "none");

        $("#enterTotalPrice").attr("class", "form-control border border-primary");
    }
};
