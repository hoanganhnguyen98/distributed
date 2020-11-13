$("#addOptions").click(function() {
    // create new food name input
    $("#newOption").append("<input class='form-control border border-primary' list='foodList' name='food_id[]' type='text' placeholder='...'>");
    // create new amount input
    $("#newOption").append("<input class='form-control border border-primary' name='amount[]' type='number' placeholder='... 123'>");
});
