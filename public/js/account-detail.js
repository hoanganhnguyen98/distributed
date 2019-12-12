// show sidebar
$("#accountSidebar").attr("class", "collapse show list-unstyled");

// edit information button
$("#editInformation").click(function() {
    // display form to edit
    $("#name").removeAttr("readonly");
    $("#address").removeAttr("readonly");
    $("#phone").removeAttr("readonly");
    $("#area").removeAttr("disabled");
    $("#role").removeAttr("disabled");

    // display suggest and button
    $("#editButton").css("display", "inline");
    $("#suggestArea").css("display", "inline");
    $("#suggestRole").css("display", "inline");

    // hidden other functions
    $("#changeImage").attr("disabled", '');
    $("#deleteAccount").attr("disabled", '');
    $(this).css("display", "none");

    // display cancael button
    $("#cancelEditButton").css("display", "inline");
});

// cancel edit information button
$("#cancelEditButton").click(function() {
    // display form to edit
    $("#name").attr("readonly", '');
    $("#address").attr("readonly", '');
    $("#phone").attr("readonly", '');
    $("#area").attr("disabled", '');
    $("#role").attr("disabled", '');

    // hidden suggest and button
    $("#editButton").css("display", "none");
    $("#suggestArea").css("display", "none");
    $("#suggestRole").css("display", "none");

    // display other functions
    $("#changeImage").removeAttr("disabled");
    $("#deleteAccount").removeAttr("disabled");
    $("#editInformation").css("display", "inline");

    // hidden cancael button
    $(this).css("display", "none");
});

// change image button
$("#changeImage").click(function() {
    // display form to edit
    $("#image").css("display", "inline");

    // hidden other functions
    $("#editInformation").attr("disabled", '');
    $("#deleteAccount").attr("disabled", '');
    $(this).css("display", "none");

    //display cancel button
    $("#cancelChangeButton").css("display", "inline");
});


// cancel change image button
$("#cancelChangeButton").click(function() {
    // display form to edit
    $("#image").css("display", "none");

    // hidden other functions
    $("#editInformation").removeAttr("disabled");
    $("#deleteAccount").removeAttr("disabled");
    $("#changeImage").css("display", "inline");

    //display cancel button
    $(this).css("display", "none");
});
